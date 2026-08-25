<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    protected $signature = 'make:module
                            {name : Nome do módulo, por exemplo Product}
                            {--no-pages : Não criar páginas React}
                            {--no-service : Não criar Service}
                            {--no-actions : Não criar Actions}
                            {--force : Substituir ficheiros existentes}';

    protected $description = 'Cria a estrutura completa de um módulo Laravel + Inertia + React/TypeScript';

    /**
     * Nome do módulo (studly) — evitar conflito com Command::$name
     */
    protected string $moduleName;

    protected string $variable;

    protected string $plural;

    protected string $route;

    protected string $permissionPrefix;
    /**
     * Lista de ficheiros criados (para sumarização)
     *
     * @var string[]
     */
    protected array $createdFiles = [];

    public function handle(): int
    {
        $this->prepareNames();

        if (empty($this->moduleName)) {
            $this->error('Nome do módulo inválido. Usa: php artisan make:module Product');

            return self::FAILURE;
        }

        $this->info("A criar módulo: {$this->moduleName}");
        $this->newLine();

        $this->createLaravelResources();
        $this->createModelFillable();
        $this->createRequests();
        $this->createPolicy();

        if (! $this->option('no-service')) {
            $this->createService();
        }

        if (! $this->option('no-actions')) {
            $this->createActions();
        }

        $this->createViewModel();
        $this->createPermissionSeeder();
        $this->createFeatureTests();
        $this->createUnitTests();

        if (! $this->option('no-pages')) {
            $this->createReactPages();
        }

        $this->appendRoutes();

        $this->printSummary();

        return self::SUCCESS;
    }

    /*
    |--------------------------------------------------------------------------
    | Names
    |--------------------------------------------------------------------------
    */

    protected function prepareNames(): void
    {
        $input = trim($this->argument('name'));

        $input = str_replace(['/', '\\'], ' ', $input);

        $this->moduleName = Str::studly($input);

        $this->variable = Str::camel($this->moduleName);

        $this->plural = Str::pluralStudly($this->moduleName);

        $this->route = Str::kebab(Str::plural($this->moduleName));

        $this->permissionPrefix = Str::kebab(Str::singular($this->moduleName));
    }

    /*
    |--------------------------------------------------------------------------
    | Laravel resources
    |--------------------------------------------------------------------------
    */

    protected function createLaravelResources(): void
    {
        $this->info('→ Model, migration, factory, seeder e controller');

        $this->call('make:model', [
            'name' => $this->moduleName,
            '--migration' => true,
            '--factory' => true,
            '--seed' => true,
            '--controller' => true,
            '--resource' => true,
        ]);
    }

    protected function createRequests(): void
    {
        $this->info('→ Form Requests');

        $this->call('make:request', [
            'name' => "Store{$this->moduleName}Request",
        ]);

        $this->call('make:request', [
            'name' => "Update{$this->moduleName}Request",
        ]);
    }

    protected function createPolicy(): void
    {
        $this->info('→ Policy');

        $this->call('make:policy', [
            'name' => "{$this->moduleName}Policy",
            '--model' => $this->moduleName,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Service
    |--------------------------------------------------------------------------
    */

    protected function createService(): void
    {
        $this->info('→ Service');

        $path = app_path("Services/{$this->moduleName}Service.php");

        $this->writeFile(
            $path,
            $this->serviceStub()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    protected function createActions(): void
    {
        $this->info('→ Actions');

        $this->writeFile(
            app_path("Actions/{$this->moduleName}/Create{$this->moduleName}Action.php"),
            $this->createActionStub()
        );

        $this->writeFile(
            app_path("Actions/{$this->moduleName}/Update{$this->moduleName}Action.php"),
            $this->updateActionStub()
        );

        $this->writeFile(
            app_path("Actions/{$this->moduleName}/Delete{$this->moduleName}Action.php"),
            $this->deleteActionStub()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ViewModel
    |--------------------------------------------------------------------------
    */

    protected function createViewModel(): void
    {
        $this->info('→ ViewModel');

        $path = app_path("ViewModels/{$this->moduleName}ViewModel.php");

        $this->writeFile(
            $path,
            $this->viewModelStub()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */

    protected function createPermissionSeeder(): void
    {
        $this->info('→ Permission seeder');

        $directory = database_path('seeders/Permissions');

        File::ensureDirectoryExists($directory);

        $path = "{$directory}/{$this->moduleName}PermissionsSeeder.php";

        $this->writeFile(
            $path,
            $this->permissionSeederStub()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Tests
    |--------------------------------------------------------------------------
    */

    protected function createFeatureTests(): void
    {
        $this->info('→ Feature tests');

        $directory = base_path("tests/Feature/{$this->plural}");

        File::ensureDirectoryExists($directory);

        $this->writeFile(
            "{$directory}/{$this->moduleName}AuthorizationTest.php",
            $this->authorizationTestStub()
        );

        $this->writeFile(
            "{$directory}/{$this->moduleName}CrudTest.php",
            $this->crudTestStub()
        );
    }

    protected function createUnitTests(): void
    {
        $this->info('→ Unit tests');

        $directory = base_path("tests/Unit/{$this->plural}");

        File::ensureDirectoryExists($directory);

        $this->writeFile(
            "{$directory}/{$this->moduleName}ServiceTest.php",
            $this->serviceTestStub()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | React / Inertia
    |--------------------------------------------------------------------------
    */

    protected function createReactPages(): void
    {
        $this->info('→ React/TypeScript pages');

        $directory = resource_path("js/pages/{$this->plural}");

        File::ensureDirectoryExists($directory);

        $pages = [
            'Index' => $this->indexPageStub(),
            'Create' => $this->createPageStub(),
            'Edit' => $this->editPageStub(),
            'Show' => $this->showPageStub(),
        ];

        foreach ($pages as $page => $content) {
            $this->writeFile(
                "{$directory}/{$page}.tsx",
                $content
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    */

    protected function appendRoutes(): void
    {
        $this->info('→ Routes');

        $routesPath = base_path('routes/web.php');

        if (! File::exists($routesPath)) {
            $this->error('routes/web.php não existe.');

            return;
        }

        $routes = $this->routeStub();

        $contents = File::get($routesPath);

        if (Str::contains($contents, "{$this->moduleName}Controller::class")) {
            $this->warn('Routes já parecem existir. Não foram duplicadas.');

            return;
        }

        File::append(
            $routesPath,
            PHP_EOL . $routes . PHP_EOL
        );
    }

    /*
    |--------------------------------------------------------------------------
    | File helper
    |--------------------------------------------------------------------------
    */

    protected function writeFile(string $path, string $contents): void
    {
        File::ensureDirectoryExists(dirname($path));

        if (File::exists($path) && ! $this->option('force')) {
            $this->warn("Já existe: {$path}");

            return;
        }

        File::put($path, $contents);

        // Registar ficheiro criado/substituído
        $this->createdFiles[] = $path;
    }

    /*
    |--------------------------------------------------------------------------
    | Stubs
    |--------------------------------------------------------------------------
    */

    protected function serviceStub(): string
    {
        return <<<PHP
<?php

namespace App\Services;

use App\Models\\{$this->moduleName};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class {$this->moduleName}Service
{
    public function paginate(int \$perPage = 15): LengthAwarePaginator
    {
        return {$this->moduleName}::query()
            ->latest()
            ->paginate(\$perPage);
    }
}
PHP;
    }

    protected function createActionStub(): string
    {
        return <<<PHP
<?php

namespace App\Actions\{$this->moduleName}s;

use App\Models\\{$this->moduleName};

class Create{$this->moduleName}Action
{
    public function handle(array \$data): {$this->moduleName}
    {
        return {$this->moduleName}::create(\$data);
    }
}
PHP;
    }

    protected function updateActionStub(): string
    {
        return <<<PHP
<?php

namespace App\Actions\{$this->moduleName}s;

use App\Models\\{$this->moduleName};

class Update{$this->moduleName}Action
{
    public function handle({$this->moduleName} \$model, array \$data): {$this->moduleName}
    {
        \$model->update(\$data);

        return \$model->refresh();
    }
}
PHP;
    }

    protected function deleteActionStub(): string
    {
        return <<<PHP
<?php

namespace App\Actions\{$this->moduleName}s;

use App\Models\\{$this->moduleName};

class Delete{$this->moduleName}Action
{
    public function handle({$this->moduleName} \$model): void
    {
        \$model->delete();
    }
}
PHP;
    }

    protected function viewModelStub(): string
    {
        return <<<PHP
<?php

namespace App\ViewModels;

use App\Models\\{$this->moduleName};

class {$this->moduleName}ViewModel
{
    public function __construct(
        protected {$this->moduleName} \$model
    ) {}

    public function toArray(): array
    {
        return [
            'id' => \$this->model->id,
            'created_at' => \$this->model->created_at?->toISOString(),
            'updated_at' => \$this->model->updated_at?->toISOString(),
        ];
    }
}
PHP;
    }

    protected function permissionSeederStub(): string
    {
        $prefix = $this->permissionPrefix;

        return <<<PHP
<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class {$this->moduleName}PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        \$permissions = [
            '{$prefix}.view',
            '{$prefix}.create',
            '{$prefix}.update',
            '{$prefix}.delete',
        ];

        foreach (\$permissions as \$permission) {
            Permission::findOrCreate(\$permission);
        }

        \$adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        \$adminRole->givePermissionTo(\$permissions);
    }
}
PHP;
    }

    protected function authorizationTestStub(): string
    {
        $prefix = $this->permissionPrefix;
        $route = $this->route;

        return <<<PHP
<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access {$this->variable}', function () {
    \$this->get('/{$route}')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    \$user = User::factory()->create();

    \$this->actingAs(\$user);

    \$this->get('/{$route}')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    \$user = User::factory()->create();

    Permission::findOrCreate('{$prefix}.view');

    \$user->givePermissionTo('{$prefix}.view');

    \$this->actingAs(\$user);

    \$this->get('/{$route}')
        ->assertSuccessful();
});
PHP;
    }

    protected function crudTestStub(): string
    {
        return <<<PHP
<?php

use App\Models\User;

it('prevents unauthorised creation of {$this->variable}', function () {
    \$user = User::factory()->create();

    \$this->actingAs(\$user);

    \$this->post('/{$this->route}', [])
        ->assertForbidden();
});
PHP;
    }

    protected function serviceTestStub(): string
    {
        return <<<PHP
<?php

use App\Services\\{$this->moduleName}Service;

it('has a {$this->moduleName} service', function () {
    expect(class_exists({$this->moduleName}Service::class))->toBeTrue();
});
PHP;
    }

    protected function indexPageStub(): string
    {
        return <<<TSX
import { Head } from '@inertiajs/react';

type {$this->moduleName}Item = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    {$this->variable}s: {
        data: {$this->moduleName}Item[];
    };
};

export default function Index({ {$this->variable}s }: Props) {
    return (
        <>
            <Head title="{$this->plural}" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    {$this->plural}
                </h1>

                <div className="mt-6">
                    {{$this->variable}s.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {{$this->variable}s.data.map((item) => (
                                <li key={item.id}>
                                    #{item.id}
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </div>
        </>
    );
}
TSX;
    }

    protected function createPageStub(): string
    {
        return <<<TSX
import { Head } from '@inertiajs/react';

export default function Create() {
    return (
        <>
            <Head title="Criar {$this->moduleName}" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Criar {$this->moduleName}
                </h1>
            </div>
        </>
    );
}
TSX;
    }

    protected function editPageStub(): string
    {
        return <<<TSX
import { Head } from '@inertiajs/react';

type Props = {
    {$this->variable}: {
        id: number;
    };
};

export default function Edit({ {$this->variable} }: Props) {
    return (
        <>
            <Head title="Editar {$this->moduleName}" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar {$this->moduleName} #{{$this->variable}.id}
                </h1>
            </div>
        </>
    );
}
TSX;
    }

    protected function showPageStub(): string
    {
        return <<<TSX
import { Head } from '@inertiajs/react';

type Props = {
    {$this->variable}: {
        id: number;
    };
};

export default function Show({ {$this->variable} }: Props) {
    return (
        <>
            <Head title="{$this->moduleName}" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    {$this->moduleName} #{{$this->variable}.id}
                </h1>
            </div>
        </>
    );
}
TSX;
    }

    protected function routeStub(): string
    {
        $controller = "App\\Http\\Controllers\\{$this->moduleName}Controller";
        $prefix = $this->permissionPrefix;
        $route = $this->route;

        return <<<PHP
// {$this->moduleName} Module
Route::middleware('auth')->group(function () {
    Route::get('/{$route}', [\\{$controller}::class, 'index'])
        ->middleware('can:{$prefix}.view')
        ->name('{$route}.index');

    Route::get('/{$route}/create', [\\{$controller}::class, 'create'])
        ->middleware('can:{$prefix}.create')
        ->name('{$route}.create');

    Route::post('/{$route}', [\\{$controller}::class, 'store'])
        ->middleware('can:{$prefix}.create')
        ->name('{$route}.store');

    Route::get('/{$route}/{{$this->variable}}', [\\{$controller}::class, 'show'])
        ->middleware('can:{$prefix}.view')
        ->name('{$route}.show');

    Route::get('/{$route}/{{$this->variable}}/edit', [\\{$controller}::class, 'edit'])
        ->middleware('can:{$prefix}.update')
        ->name('{$route}.edit');

    Route::put('/{$route}/{{$this->variable}}', [\\{$controller}::class, 'update'])
        ->middleware('can:{$prefix}.update')
        ->name('{$route}.update');

    Route::delete('/{$route}/{{$this->variable}}', [\\{$controller}::class, 'destroy'])
        ->middleware('can:{$prefix}.delete')
        ->name('{$route}.destroy');
});
PHP;
    }

    /*
    |--------------------------------------------------------------------------
    | Preencher $fillable no Model
    |--------------------------------------------------------------------------
    */

    protected function createModelFillable(): void
    {
        $this->info('→ A preencher $fillable no Model');

        // 1. Encontrar migration do módulo
        $migrationPath = database_path('migrations');
        $files = File::files($migrationPath);

        $migrationFile = null;

        foreach ($files as $file) {
            if (str_contains($file->getFilename(), strtolower($this->moduleName))) {
                $migrationFile = $file->getPathname();
                break;
            }
        }

        if (! $migrationFile) {
            $this->warn("⚠ Não encontrei migration para {$this->moduleName}. Ignorar fillable.");
            return;
        }

        // 2. Ler conteúdo da migration
        $contents = File::get($migrationFile);

        // Captura todos os $table->tipo('campo')
        preg_match_all('/\$table->\w+\(\'(.*?)\'\)/', $contents, $matches);

        $columns = [];

        foreach ($matches[1] as $column) {
            if (! in_array($column, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                $columns[] = $column;
            }
        }

        if (empty($columns)) {
            $this->warn("⚠ Migration encontrada, mas sem colunas utilizáveis.");
            return;
        }

        // 3. Construir string do fillable
        $fillableString = "protected \$fillable = [\n        '"
            . implode("',\n        '", $columns)
            . "',\n    ];";

        // 4. Caminho do Model
        $modelPath = app_path("Models/{$this->moduleName}.php");

        if (! File::exists($modelPath)) {
            $this->warn("⚠ Model {$this->moduleName} ainda não existe. Ignorar fillable.");
            return;
        }

        $modelContents = File::get($modelPath);

        // 5. Inserir ou substituir fillable
        if (str_contains($modelContents, 'protected $fillable')) {
            // Substituir
            $modelContents = preg_replace(
                '/protected \$fillable = 

\[.*?\]

;/s',
                $fillableString,
                $modelContents
            );
        } else {
            // Inserir logo após a declaração da classe
            $modelContents = preg_replace(
                '/class ' . $this->moduleName . ' extends Model\s*\{/',
                "class {$this->moduleName} extends Model\n{\n    {$fillableString}\n",
                $modelContents
            );
        }

        File::put($modelPath, $modelContents);

        $this->info('✔ $fillable gerado com sucesso');
    }


    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    protected function printSummary(): void
    {
        $this->newLine();

        $this->info('✓ Módulo criado com sucesso.');

        $this->newLine();

        $this->line("Nome:        {$this->moduleName}");
        $this->line("Plural:      {$this->plural}");
        $this->line("Route:       /{$this->route}");
        $this->line("Permissions: {$this->permissionPrefix}.*");

        $this->newLine();

        $this->comment('Próximos passos:');

        $this->line('1. Rever a migration.');
        $this->line('2. Definir $fillable no Model.');
        $this->line('3. Definir as regras dos Form Requests.');
        $this->line('4. Implementar a Policy.');
        $this->line('5. Registar o Permission Seeder no DatabaseSeeder.');
        $this->line('6. Implementar o Controller.');
        $this->line('7. Executar os testes.');

        if (! empty($this->createdFiles)) {
            $this->newLine();

            $this->comment('Ficheiros criados / modificados:');

            foreach ($this->createdFiles as $file) {
                $this->line(" - {$file}");
            }
        }
    }
}
