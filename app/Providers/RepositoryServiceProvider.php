<?php

namespace App\Providers;

use App\Repositories\AdminDashboard\AdminDashboardInterface;
use App\Repositories\AdminDashboard\AdminDashboardRepository;
use App\Repositories\Auth\AuthInterface;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\Backup\BackupInterface;
use App\Repositories\Backup\BackupRepository;
use App\Repositories\BranchDashboard\BranchDashboardInterface;
use App\Repositories\BranchDashboard\BranchDashboardRepository;
use App\Repositories\BusinessDashboard\BusinessDashboardInterface;
use App\Repositories\BusinessDashboard\BusinessDashboardRepository;
use App\Repositories\CrudGenerator\CrudGeneratorInterface;
use App\Repositories\CrudGenerator\CrudGeneratorRepository;
use App\Repositories\GeneralSettings\GeneralSettingsInterface;
use App\Repositories\GeneralSettings\GeneralSettingsRepository;
use App\Repositories\Installer\InstallerInterface;
use App\Repositories\Installer\InstallerRepository;
use App\Repositories\Language\LanguageInterface;
use App\Repositories\Language\LanguageRepository;
use App\Repositories\LoginActivity\LoginActivityInterface;
use App\Repositories\LoginActivity\LoginActivityRepository;
use App\Repositories\LoginSettings\LoginSettingInterface;
use App\Repositories\LoginSettings\LoginSettingRepository;
use App\Repositories\MailSettings\MailSettingsInterface;
use App\Repositories\MailSettings\MailSettingsRepository;
use App\Repositories\Profile\ProfileInterface;
use App\Repositories\Profile\ProfileRepository;
use App\Repositories\Role\RoleInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\TodoList\TodoListInterface;
use App\Repositories\TodoList\TodoListRepository;

use App\Repositories\Project\ProjectInterface;
use App\Repositories\Project\ProjectRepository;
use App\Repositories\Recaptcha\ReCaptchaInterface;
use App\Repositories\Recaptcha\ReCaptchaRepository;
use App\Repositories\Settings\SettingsInterface;
use App\Repositories\Settings\SettingsRepository;
use App\Repositories\Upload\UploadInterface;
use App\Repositories\Upload\UploadRepository;
use App\Repositories\User\UserInterface;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
         $this->app->bind(ProfileInterface::class,            ProfileRepository::class);
         $this->app->bind(UploadInterface::class,             UploadRepository::class);
         $this->app->bind(RoleInterface::class,               RoleRepository::class);
         $this->app->bind(UserInterface::class,               UserRepository::class);
         $this->app->bind(TodoListInterface::class,           TodoListRepository::class);
         $this->app->bind(ProjectInterface::class,            ProjectRepository::class);
         $this->app->bind(LanguageInterface::class,           LanguageRepository::class);
         $this->app->bind(AuthInterface::class,               AuthRepository::class);
         $this->app->bind(LoginActivityInterface::class,      LoginActivityRepository::class);
         $this->app->bind(CrudGeneratorInterface::class,      CrudGeneratorRepository::class);
         $this->app->bind(BackupInterface::class,             BackupRepository::class);
         $this->app->bind(MailSettingsInterface::class,       MailSettingsRepository::class);//mail settings
         $this->app->bind(SettingsInterface::class,           SettingsRepository::class);    //settings

        //dashboard 
        $this->app->bind(AdminDashboardInterface::class,     AdminDashboardRepository::class);
        $this->app->bind(BusinessDashboardInterface::class,  BusinessDashboardRepository::class);
        $this->app->bind(BranchDashboardInterface::class,    BranchDashboardRepository::class);
        //view composer provider 
        $this->app->register(ViewComposerServiceProvider::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
