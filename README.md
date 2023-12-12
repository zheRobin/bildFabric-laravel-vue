# Bildfabrik

## Installation

Alternative installation is possible without local dependencies relying on [Docker](#docker).

Clone the repository (replace username with your own):

```bash
git clone https://username@bitbucket.org/rkablaoui/bildfabrik.git
```

Switch to the repo folder:

```bash
cd bildfabrik
```

Install all the dependencies using composer:

```bash
composer install
```

Copy the example env file and make the required configuration changes in the .env file:

```bash
cp .env.example .env
```

Generate a new application key:

```bash
php artisan key:generate
```

Run the database migrations (Set the database connection in .env before migrating):

```bash
php artisan migrate
```

## Docker

To install with [Docker](https://www.docker.com), run following commands:

Clone the repository (replace username with your own):

```bash
git clone https://username@bitbucket.org/rkablaoui/bildfabrik.git
```

Switch to the repo folder:

```bash
cd bildfabrik
```

Install all the dependencies using composer docker image (or use your own):

```bash
docker run --rm -v $(pwd):/app composer:latest install
```
Copy the example env file and make the required configuration changes in the .env file:

```bash
cp .env.example .env
```

Use [Sail](https://laravel.com/docs/10.x/sail#introduction) command-line interface for interacting with Laravel's default Docker development environment.

By default, all commands are invoked using the **vendor/bin/sail** script.
Instead of repeatedly typing vendor/bin/sail to execute Sail commands, you may wish to configure a shell alias that allows you to execute Sail's commands more easily:

```bash
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

To make sure this is always available, you may add this to your shell configuration file in your home directory, such as ~/.zshrc or ~/.bashrc, and then restart your shell.
Once the shell alias has been configured, you may execute Sail commands by simply typing **sail**

To start all the Docker containers in the background, you may start Sail in "detached" mode:

```bash
sail up -d
```

Generate a new application key:

```bash
sail artisan key:generate
```

Run the database migrations (Set the database connection in .env before migrating):

```bash
sail artisan migrate
```
