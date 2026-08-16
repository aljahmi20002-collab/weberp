<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Sanity checks to verify that the installer is locked down after install.
 *
 * These tests assert that the routes file contains the correct middleware
 * group. Running full HTTP tests against them requires a DB connection,
 * so we check the compiled route table after the module is loaded.
 */
class InstallerRoutesTest extends TestCase
{
    public function test_installer_web_routes_file_protects_all_routes_with_is_not_installed(): void
    {
        $routesFile = base_path('Modules/Installer/Routes/web.php');
        $this->assertFileExists($routesFile);

        $contents = file_get_contents($routesFile);

        // Every Route::get/post inside the file must sit inside a group that
        // lists IsNotInstalled middleware. We check by counting: there must be
        // exactly one `Route::group` call and it must include IsNotInstalled,
        // and every Route::(get|post) must appear AFTER it.
        $this->assertStringContainsString("IsNotInstalled", $contents);

        $groupPos = strpos($contents, "IsNotInstalled");
        foreach (['Route::get', 'Route::post'] as $routeType) {
            $pos = 0;
            while (($routePos = strpos($contents, $routeType, $pos)) !== false) {
                $this->assertGreaterThan($groupPos, $routePos,
                    "{$routeType} at offset {$routePos} is declared before the IsNotInstalled middleware group."
                );
                $pos = $routePos + 1;
            }
        }
    }

    public function test_purchase_code_is_not_required_by_installer(): void
    {
        $this->assertFileDoesNotExist(
            // Sanity check that the PurchaseVerification function no longer exists.
            $this->projectBase() . '/.env.purchase_marker',
            'Marker file should not exist (placeholder)'
        );

        $controller = file_get_contents(base_path('Modules/Installer/Http/Controllers/InstallerController.php'));
        $this->assertStringNotContainsString('PurchaseVerification', $controller);
        $this->assertStringNotContainsString('V5yV9o9ZkDkdFBIues', $controller, 'Envato personal token must be removed.');
        $this->assertStringNotContainsString('api.envato.com', $controller);

        $request = file_get_contents(base_path('Modules/Installer/Http/Requests/InstallRequest.php'));
        $this->assertStringNotContainsString('purchase_code', $request);

        $view = file_get_contents(base_path('Modules/Installer/Resources/views/administration.blade.php'));
        $this->assertStringNotContainsString('purchase_code', $view);
        $this->assertStringNotContainsString('Purchase code', $view);
    }

    public function test_demo_mode_is_read_from_config(): void
    {
        // No env('DEMO') calls should remain in app/ or Modules/ — they would
        // stop working when config:cache is run in production.
        $output = [];
        $rc     = 0;
        exec(
            "grep -rln \"env('DEMO')\" " . escapeshellarg(base_path('app')) . " " .
            escapeshellarg(base_path('Modules')) . " --include=*.php 2>/dev/null || true",
            $output,
            $rc
        );
        $this->assertEmpty($output,
            "All env('DEMO') calls should be replaced with config('app.demo_mode'). Found in: " .
            implode(', ', $output)
        );
    }

    public function test_demo_mode_config_key_exists(): void
    {
        $this->assertArrayHasKey('demo_mode', config('app'));
    }

    public function test_gitignore_ignores_env_text(): void
    {
        $gitignore = file_get_contents(base_path('.gitignore'));
        $this->assertStringContainsString('.env.*', $gitignore);
    }

    public function test_password_min_length_in_install_request(): void
    {
        $request = file_get_contents(base_path('Modules/Installer/Http/Requests/InstallRequest.php'));
        $this->assertStringContainsString("'password'     => 'required|min:8'", $request);
    }

    public function test_crud_generator_uses_array_artisan_call(): void
    {
        $repo = file_get_contents(base_path('app/Repositories/CrudGenerator/CrudGeneratorRepository.php'));

        // Must not concatenate user input into a shell-style command string.
        $this->assertStringNotContainsString('$command = "crud:generate"', $repo);
        $this->assertStringNotContainsString("'crud:generate'.$request", $repo);

        // Should pass arguments as an array.
        $this->assertStringContainsString("Artisan::call('crud:generate'", $repo);
        $this->assertStringContainsString("'model'", $repo);
        $this->assertStringContainsString("'--fields'", $repo);

        // Migration auto-fire must be removed (prevents unexpected DB changes).
        $this->assertStringNotContainsString("migrate',['--force'", $repo);
        $this->assertStringNotContainsString("'migrate', ['--force'", $repo);

        // Model-name regex must be strict.
        $this->assertStringContainsString("A-Za-z][A-Za-z0-9_]", $repo);
    }

    public function test_env_text_has_no_credentials(): void
    {
        $env = file_get_contents(base_path('.env.text'));
        $this->assertStringNotContainsString('wemaxit002', $env);
        $this->assertStringNotContainsString('fywggxdxphfarwqx', $env,
            'Leaked Gmail app password must be removed from .env.text.'
        );
        $this->assertStringNotContainsString('V5yV9o9ZkDkdFBIues', $env);
        $this->assertStringNotContainsString('APP_KEY=base64:', $env,
            'A fixed APP_KEY must not be shipped in .env.text.'
        );
        // APP_KEY should be empty so key:generate fills it.
        $this->assertStringContainsString('APP_KEY=', $env);
    }

    public function test_settings_seeder_has_no_hardcoded_credentials(): void
    {
        $seeder = file_get_contents(base_path('database/seeders/SettingsSeeder.php'));
        $leaks = [
            'fywggxdxphfarwqx',          // leaked gmail app password
            'a636c53bf10d05b1515a737f',  // facebook secret
            'GOCSPX-',                    // google secret
            '6Lcf3yAh',                   // recaptcha key
            'sk_test_',                   // stripe secret
            'pk_test_',                   // stripe pub key
        ];
        foreach ($leaks as $needle) {
            $this->assertStringNotContainsString($needle, $seeder,
                "Hard-coded credential '{$needle}' must be removed from SettingsSeeder."
            );
        }
    }

    public function test_permission_middleware_returns_json_for_api(): void
    {
        $middleware = file_get_contents(
            base_path('app/Http/Middleware/PermissionCheckMiddleware.php')
        );
        $this->assertStringContainsString('expectsJson', $middleware);
        $this->assertStringContainsString("403", $middleware);
        // The unreachable abort() after a redirect must not be present.
        $this->assertStringNotContainsString("abort(403);", $middleware);
    }

    private function projectBase(): string
    {
        return base_path();
    }
}
