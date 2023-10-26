# install depdendencies
docker-compose exec php-app composer install

# create migrations
docker-compose exec php-app bin/cake bake migration NameOfMigration

# run migrations
docker-compose exec php-app bin/cake migrations migrate


# create controller
bin/cake bake controller Name