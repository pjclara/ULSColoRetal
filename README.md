# Startup Kit

Base reutilizável para aplicações SaaS com Laravel + React.

## Stack

- Laravel
- React
- TypeScript
- Inertia
- Tailwind CSS
- PostgreSQL
- Redis
- Pest
- PHPStan / Larastan
- Laravel Pint
- ESLint
- Prettier
- GitHub Actions

## Requisitos

- PHP
- Composer
- Node.js
- npm
- PostgreSQL
- Redis

## Instalação

```bash
git clone <repository>
cd startup-kit

composer install
npm install

cp .env.example .env

php artisan key:generate

php artisan migrate

npm run build