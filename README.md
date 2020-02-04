Sample Symfony / Doctrine ORM application talking to two separate databases.

Source entity defines `user_details` table for Source database, and Destination entity defines `customer_details` table for Destination database.

## Running app

First of all copy `.env.dist` to `.env`, there is no need to update anything here.
Secondly add a `symfony.local` to your `/etc/hosts`, pointint to `127.0.0.1`.

### To start Docker containers type:

```bash
docker-compose up -d
```

This will create all needed containers.

### Composer

Next you need to run `composer` to get all needed packages.

```bash
composer install
```

### CLI commands

App was designed only as a CLI client, but still has some default HTTP frontend.

All commands can be executed through composer, or through the Symfony console.

#### Migrations

Migrations make sure all the database table shcemas are executed properly.
Run them first.

```bash
composer run-migrations
```

OR

```bash
php bin/console doctrine:migrations:execute --up 20200204162840 --em=source
php bin/console doctrine:migrations:execute --up 20200204161316 --em=destination
```

#### Source database data

This command will generate 55 random entries in the Source database table.
It's using `fzaninotto/faker` library that generates fake data for you.

```bash
composer generate-random-rows
```

OR

```bash
php bin/console app:generate-random-data 55
```

#### Moving data

Next step for this application is to move data from one database to another.
This command maps and copies rows from Source database table to Destination.

```bash
composer move-data
```

OR

```bash
php bin/console app:move-data
```

#### Cleanup databases

When doing multiple tests a quick cleanup is a nice to have.
This destroys all data and table structures in both Source and Destination databases. Running migrations is required after Cleanup.

```bash
composer clean-databases
```

OR

```bash
php bin/console doctrine:schema:drop --em=source --full-database --force
php bin/console doctrine:schema:drop --em=destination --full-database --force
```
