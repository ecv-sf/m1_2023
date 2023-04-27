# Cours Symfony

## Outils

```
composer require --dev friendsofphp/php-cs-fixer
./bin/php-cs-fixer fix src
```

## Import TAN

Défaut
```
php bin/console d:s:u --force
php bin/console app:import-gtfs Route
php bin/console app:import-gtfs Stop
php bin/console app:import-gtfs Trip
php bin/console app:import-gtfs StopTime --no-debug
```

Postgres
```
php bin/console d:d:d --force --env=postgres
php bin/console d:d:c --env=postgres
php bin/console d:s:u --force --env=postgres
php bin/console app:import-gtfs Route --env=postgres
php bin/console app:import-gtfs Stop --env=postgres
php bin/console app:import-gtfs Trip --env=postgres 
php bin/console app:import-gtfs StopTime --env=postgres --no-debug
```

MySQL
```
php bin/console d:d:d --force --env=mysql
php bin/console d:d:c --env=mysql
php bin/console d:s:u --force --env=mysql
php bin/console app:import-gtfs Route --env=mysql
php bin/console app:import-gtfs Stop --env=mysql
php bin/console app:import-gtfs Trip --env=mysql 
php bin/console app:import-gtfs StopTime --env=mysql --no-debug
```

SQLITE
```
php bin/console d:d:d --force --env=sqlite
php bin/console d:d:c --env=sqlite
php bin/console d:s:u --force --env=sqlite
php bin/console app:import-gtfs Route --env=sqlite
php bin/console app:import-gtfs Stop --env=sqlite
php bin/console app:import-gtfs Trip --env=sqlite 
php bin/console app:import-gtfs StopTime --env=sqlite --no-debug
```