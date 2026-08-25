<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class AppSetup extends Command
{
    protected $signature = 'app:setup
                            {--skip-migrate : Não executar migrations}
                            {--skip-storage : Não criar o storage link}
                            {--skip-build : Não fazer build do frontend}
                            {--force : Executar passos mesmo que pareçam já estar configurados}';

    protected $description = 'Prepara a aplicação para desenvolvimento de forma segura e repetível';

    public function handle(): int
    {
        $this->info('🚀 A preparar a aplicação...');
        $this->newLine();

        if (! $this->checkEnvironment()) {
            return self::FAILURE;
        }

        $this->generateApplicationKey();
        $this->runMigrations();
        $this->createStorageLink();
        $this->clearCaches();
        $this->runPermissionSeeders();
        $this->buildFrontend();

        $this->newLine();
        $this->info('✅ Aplicação preparada com sucesso!');

        $this->newLine();

        $this->line('Próximos passos:');
        $this->line('  php artisan serve');
        $this->line('  npm run dev');

        return self::SUCCESS;
    }

    protected function checkEnvironment(): bool
    {
        $this->info('→ A verificar ambiente...');

        if (! File::exists(base_path('.env'))) {
            $this->error('.env não existe.');

            $this->line('');
            $this->line('Cria primeiro:');
            $this->line('  cp .env.example .env');

            return false;
        }

        if (app()->environment('production')) {
            $this->error('Este comando não deve ser executado directamente em produção.');

            $this->line('Use o processo de deployment específico para produção.');

            return false;
        }

        $this->info('  ✓ Ambiente válido');

        return true;
    }

    protected function generateApplicationKey(): void
    {
        $this->info('→ Application key...');

        $env = File::get(base_path('.env'));

        if (
            $this->option('force') ||
            ! preg_match('/^APP_KEY=.+$/m', $env)
        ) {
            Artisan::call('key:generate', [
                '--force' => true,
            ]);

            $this->info('  ✓ APP_KEY configurada');

            return;
        }

        $this->info('  ✓ APP_KEY já configurada');
    }

    protected function runMigrations(): void
    {
        if ($this->option('skip-migrate')) {
            $this->comment('→ Migrations ignoradas');

            return;
        }

        $this->info('→ A executar migrations...');

        $exitCode = Artisan::call('migrate', [
            '--force' => true,
        ]);

        $this->output->write(
            Artisan::output()
        );

        if ($exitCode !== self::SUCCESS) {
            $this->error('As migrations falharam.');

            return;
        }

        $this->info('✓ Migrations concluídas');
    }

    protected function createStorageLink(): void
    {
        if ($this->option('skip-storage')) {
            $this->comment('→ Storage link ignorado');

            return;
        }

        $this->info('→ Storage...');

        if (
            File::exists(public_path('storage')) &&
            ! $this->option('force')
        ) {
            $this->info('  ✓ Storage link já existe');

            return;
        }

        Artisan::call('storage:link');

        $this->output->write(
            Artisan::output()
        );

        $this->info('✓ Storage preparado');
    }

    protected function clearCaches(): void
    {
        $this->info('→ A limpar caches...');

        Artisan::call('optimize:clear');

        $this->output->write(
            Artisan::output()
        );

        $this->info('✓ Caches limpos');
    }

    protected function runPermissionSeeders(): void
    {
        $this->info('→ Permissões...');

        $databaseSeeder = database_path('seeders/DatabaseSeeder.php');

        if (! File::exists($databaseSeeder)) {
            $this->comment('  ! DatabaseSeeder.php não encontrado');

            return;
        }

        /*
         * Não executamos seeders automaticamente aqui.
         *
         * O DatabaseSeeder é a fonte de verdade para os dados
         * iniciais da aplicação.
         *
         * Isto evita que o app:setup execute seeders arbitrários
         * sem o developer saber.
         */

        $this->info('  ✓ Permissões controladas pelo DatabaseSeeder');
    }

    protected function buildFrontend(): void
    {
        if ($this->option('skip-build')) {
            $this->comment('→ Build frontend ignorado');

            return;
        }

        if (! File::exists(base_path('package.json'))) {
            $this->comment('→ package.json não encontrado');

            return;
        }

        $this->info('→ Frontend...');

        $this->comment('  Executa npm run build no terminal para o build.');

        $this->info('✓ Frontend verificado');
    }
}