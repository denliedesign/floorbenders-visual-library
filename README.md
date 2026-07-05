# Floorbenders Visual Library

Floorbenders Visual Library is a Laravel-based movement atlas for organizing Floorbenders positions, realms, gates, transitions, movements, and phrases. The site includes a public-facing library and taxonomy pages, plus an authenticated admin area for managing movement records, uploaded media, phrase/sequence data, and generated previews.

## Project purpose

This project supports the Floorbenders movement system by turning the curriculum into a searchable visual library. It is designed to help movers, teachers, and future students explore movement positions, understand taxonomy, and eventually build phrases from reusable movement clips.

Core goals:

- Organize Floorbenders movements into a structured atlas.
- Show public movement, gate, note, and taxonomy pages.
- Provide an admin interface for managing movements and phrases.
- Store movement media and generated sequence previews.
- Use FFmpeg to process media for phrase previews.
- Prepare the foundation for a future user-facing Phrase Builder.

## Tech stack

This project is built primarily with the TALL stack:

- Tailwind CSS
- Alpine.js
- Laravel
- Livewire

Additional technologies and services include:

- PHP
- MySQL
- Blade
- Vite
- JavaScript
- FFmpeg / FFprobe for media processing
- Laravel queues for longer-running media jobs
- Laravel Forge for deployment
- DigitalOcean server hosting

## Main areas of the app

The app currently includes:

- Public Movement Atlas
- Public sequence / phrase pages
- Taxonomy pages
- Admin dashboard
- Admin movement management
- Admin sequence / phrase management
- Media uploads and generated previews

## Local setup

Clone the repository:

```bash
git clone git@github.com:denliedesign/floorbenders-visual-library.git
cd floorbenders-visual-library
```

Install PHP dependencies:

```bash
composer install
```

Install JavaScript dependencies:

```bash
npm install
```

Copy the environment file:

```bash
cp .env.example .env
```

Generate an app key:

```bash
php artisan key:generate
```

Create a local MySQL database, then update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_local_database_name
DB_USERNAME=root
DB_PASSWORD=your_local_mysql_password
```

Run migrations:

```bash
php artisan migrate
```

Start the local Laravel server:

```bash
php artisan serve
```

Start Vite:

```bash
npm run dev
```

## Media and storage

Uploaded movement media and generated sequence files are stored through Laravel's public storage system.

Run this locally after setup:

```bash
php artisan storage:link
```

Important:

- Do not commit uploaded media files unless intentionally versioning sample media.
- Do not commit large generated previews.
- Do not commit local SQL exports.
- Production media should be copied to the server's shared storage location when deploying or restoring the app.

Common media paths include:

```text
storage/app/public/movements
storage/app/public/sequences
```

The public symlink points from:

```text
public/storage
```

to:

```text
storage/app/public
```

## FFmpeg setup

The app uses FFmpeg and FFprobe for media processing.

Check whether FFmpeg is installed:

```bash
ffmpeg -version
ffprobe -version
```

On Ubuntu production servers:

```bash
sudo apt update
sudo apt install ffmpeg -y
```

Then confirm the paths:

```bash
which ffmpeg
which ffprobe
```

Production `.env` should include:

```env
FFMPEG_PATH=/usr/bin/ffmpeg
FFPROBE_PATH=/usr/bin/ffprobe
```

After changing `.env`, clear Laravel config/cache:

```bash
php artisan optimize:clear
```

## Queue worker

Media processing can require Laravel queues.

For local development, run:

```bash
php artisan queue:work
```

For production, configure a queue worker in Laravel Forge:

```bash
php artisan queue:work
```

Recommended production queue settings:

```text
Connection: database
Queue: default
Timeout: 900
Tries: 2
```

After deployments or `.env` changes:

```bash
php artisan queue:restart
```

## Database notes

For a fresh local setup:

```bash
php artisan migrate
```

For production after the first full database import, normal future deploys should use:

```bash
php artisan migrate --force
```

Do not use these commands on production unless you intentionally want to destroy or reset data:

```bash
php artisan migrate:fresh
php artisan migrate:refresh
php artisan migrate:reset
```

## Production deployment

Production is deployed with Laravel Forge using zero-downtime deployment.

Recommended Forge deploy script:

```bash
$CREATE_RELEASE()

cd $FORGE_RELEASE_DIRECTORY

composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

npm install
npm run build

php artisan migrate --force

php artisan storage:link

php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

$ACTIVATE_RELEASE()

$RESTART_QUEUES()
```

Production site path:

```text
/home/forge/floorbenders.com/current
```

Useful production commands:

```bash
cd /home/forge/floorbenders.com/current

php artisan optimize:clear
php artisan migrate:status
php artisan storage:link
php artisan queue:restart
```

Check Laravel logs:

```bash
tail -n 100 storage/logs/laravel.log
```

## Production database connection

When connecting to the production database from MySQL Workbench, use an SSH tunnel.

One reliable method is to create the tunnel manually in PowerShell:

```powershell
ssh -i "$env:USERPROFILE\.ssh\forge_workbench_rsa" -L 3307:127.0.0.1:3306 forge@YOUR_SERVER_IP -N
```

Then connect MySQL Workbench using:

```text
Connection Method: Standard TCP/IP
Hostname: 127.0.0.1
Port: 3307
Username: forge
Password: Forge database password
Default Schema: production database name
```

The local MySQL `root` user is only for the local database. Production uses the MySQL user and password created by Forge.

## Environment files

Never commit real environment files.

Do not commit:

```text
.env
.env.production
*.sql
storage/app/public/*
```

Use `.env.example` for safe placeholder configuration.

## Common troubleshooting

Clear Laravel caches:

```bash
php artisan optimize:clear
```

Check migration status:

```bash
php artisan migrate:status
```

Check database counts in Tinker:

```bash
php artisan tinker
```

```php
App\Models\User::count();
App\Models\Movement::count();
App\Models\Sequence::count();
```

Check FFmpeg:

```bash
ffmpeg -version
ffprobe -version
```

Check logs:

```bash
tail -n 100 storage/logs/laravel.log
```

## Current deployment reminders

- Confirm production `.env` points to the correct database.
- Confirm imported production database has users, movements, sequences, and media records.
- Confirm `php artisan storage:link` has been run.
- Confirm FFmpeg and FFprobe are installed on production.
- Confirm the Forge queue worker is running if media processing jobs are used.
- Confirm `php artisan migrate --force` is present in the Forge deploy script after the initial database import is complete.

## Project ownership

This project is part of the Floorbenders movement system and curriculum. Code, media, taxonomy language, movement organization, and phrase-building concepts should be treated as project-specific intellectual property.
