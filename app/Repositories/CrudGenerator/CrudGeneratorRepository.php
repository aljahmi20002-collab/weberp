<?php

namespace App\Repositories\CrudGenerator;

use App\Models\Backend\CrudGenerator;
use App\Repositories\CrudGenerator\CrudGeneratorInterface;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class CrudGeneratorRepository implements CrudGeneratorInterface
{
    /**
     * Safe list of allowed DB column types that can be passed to the
     * crud:generate command. Anything outside this list is rejected to
     * prevent command-injection / RCE via the --fields flag.
     */
    private const ALLOWED_DB_TYPES = [
        'string', 'char', 'text', 'mediumText', 'longText', 'integer',
        'bigInteger', 'smallInteger', 'tinyInteger', 'float', 'double',
        'decimal', 'boolean', 'date', 'dateTime', 'dateTimeTz', 'time',
        'timestamp', 'timestampTz', 'binary', 'json', 'jsonb', 'uuid',
    ];

    public function store($request)
    {
        try {
            // ---- Validate model name: only ASCII letters/numbers/underscore,
            // must start with a letter. This prevents shell/option injection
            // via Artisan::call().
            $modelName = (string) $request->model_name;
            if (!preg_match('/^[A-Za-z][A-Za-z0-9_]{0,60}$/', $modelName)) {
                throw ValidationException::withMessages([
                    'model_name' => ['Model name may only contain letters, numbers, underscore and must start with a letter.'],
                ]);
            }

            $tableName = Str::lower(Str::plural(Str::snake($modelName)));
            if (Schema::hasTable($tableName)) {
                throw ValidationException::withMessages([
                    'model_name' => ["A table for model `{$modelName}` ({$tableName}) already exists."],
                ]);
            }

            // ---- Validate each field
            $fields = $request->input('field', []);
            $safeFieldsParts = [];
            foreach ($fields as $input) {
                $fieldName = (string) ($input['field_name'] ?? '');
                $dbType    = (string) ($input['db_type'] ?? '');

                if (!preg_match('/^[a-z_][a-z0-9_]{0,60}$/', $fieldName)) {
                    throw ValidationException::withMessages([
                        'field' => ["Invalid field name: `{$fieldName}`. Use only lowercase letters, numbers and underscore."],
                    ]);
                }
                if (!in_array($dbType, self::ALLOWED_DB_TYPES, true)) {
                    throw ValidationException::withMessages([
                        'field' => ["Invalid DB type `{$dbType}` for field `{$fieldName}`."],
                    ]);
                }
                $safeFieldsParts[] = Str::lower($fieldName) . '#' . $dbType;
            }
            $safeFields = ' ' . implode(';', $safeFieldsParts) . ';';

            $Crudgenerator             = new CrudGenerator();
            $Crudgenerator->title      = $request->title;
            $Crudgenerator->model_name = $modelName;
            $Crudgenerator->icon_class = $request->icon_class;
            $Crudgenerator->fields     = $request->field;
            $Crudgenerator->save();

            // ---- Call Artisan using an array of arguments/options so
            // Laravel handles escaping properly. No shell interpolation.
            Artisan::call('crud:generate', [
                'model'            => $modelName,
                '--fields'         => $safeFields,
                '--view-path'      => 'crudgenerator',
                '--controller-namespace' => 'Crudgenerator',
                '--form-helper'    => 'html',
            ]);

            // NOTE: automatic migration on the fly is intentionally DISABLED
            // because it can destroy data if run in production. The developer
            // can run `php artisan migrate` manually after reviewing the
            // generated files.

            return true;
        } catch (ValidationException $e) {
            // Let validation errors bubble up to the form.
            throw $e;
        } catch (\Throwable $th) {
            report($th);
            return false;
        }
    }
}
