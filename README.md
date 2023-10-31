# Table of Contents

1. [Development Environment](#development-environment)
2. [Tutorial CakePHP](#tutorial-cakephp) 

# Development Environment

This application is defined with [Docker Compose](https://docs.docker.com/compose/) V1.
Docker Compose is included in [Docker Desktop](https://docs.docker.com/desktop/).

## Ports

Docker Compose for development environment exposes following ports:

- 80 (HTTP) http://localhost/
- 3306 (MySQL) username and password are set in [docker-compose.yml](docker-compose.yml)

If another application is using these ports, Docker Compose can't start.
Please stop that application before starting Docker Compose.

## PHP

Docker Compose has `php-app` service.
Use it to run Composer or CakePHP commands.
Commands in Docker Compose run at [app](app) directory.

For example, run following command after `docker-compose up` to install dependencies:

```sh
docker-compose exec php-app composer install
```

### Debugging

The Docker Compose for `php-app` service had been configured with debugging engine Xdebug.
Please configure the IDE with debugging support for Xdebug.

### Basic Coding Standard & Quality

To ensure the written code following the basic coding standard & quality, we've configured the Docker Compose for `php-app` service with tools:
1. PHP Code Sniffer (PHPCS) based on CakePHP coding standard rules.
2. PHPStan - PHP Static Analysis Tool.

Everytime making a changes to the code, please run both commands as follows:

```sh
docker-compose exec php-app composer cs-check
```

```sh
docker-compose exec php-app composer stan
```

If either both tools complaint about errors, then please fix following the basic coding standard & quality.

# Tutorial CakePHP

We'll practice the web development using CakePHP Framework following https://book.cakephp.org/4/en/tutorials-and-examples.html

## Instruction

1. Please fork this repository.
2. Please create 1 PR for each topic. For example:

| PR # | Branch name                     | Based branch                    | PR Title                         | Memo                                                                                                                                                                                                                                                                                                                                                                                                                    |
|:-----|:--------------------------------|:--------------------------------|:---------------------------------|:------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| 1    | cakephp-tutorial-database       | main                            | Creating the Database            | Reference [CMS Tutorial - Creating the Database](https://book.cakephp.org/4/en/tutorials-and-examples/cms/database.html) <br/> - Once created the tables, please refer [CakePHP Migration](https://book.cakephp.org/migrations/3/en/index.html). Please do create initial migration from the existing database.                                                                                                         |
| 2    | cakephp-tutorial-articles       | cakephp-tutorial-database       | Creating the Articles Controller | Reference [CMS Tutorial - Creating the Articles Controller](https://book.cakephp.org/4/en/tutorials-and-examples/cms/articles-controller.html).                                                                                                                                                                                                                                                                         |
| 3    | cakephp-tutorial-tags-and-users | cakephp-tutorial-articles       | Tags and Users                   | Reference [CMS Tutorial - Tags and Users](https://book.cakephp.org/4/en/tutorials-and-examples/cms/tags-and-users.html)                                                                                                                                                                                                                                                                                                 |
| 4    | cakephp-tutorial-authentication | cakephp-tutorial-tags-and-users | Authentication                   | Reference [CMS Tutorial - Authentication](https://book.cakephp.org/4/en/tutorials-and-examples/cms/authentication.html)                                                                                                                                                                                                                                                                                                 |
| 5    | cakephp-tutorial-authorization  | cakephp-tutorial-authentication | Authorization                    | Reference [CMS Tutorial - Authorization](https://book.cakephp.org/4/en/tutorials-and-examples/cms/authorization.html)                                                                                                                                                                                                                                                                                                   |

3. Please kindly share the URL of the Pull Request once finish the development.
4. **Important Note:** Please make a small & meaningful commits.


# LESSONS

# create migrations
docker-compose exec php-app bin/cake bake migration NameOfMigration

# run migrations
docker-compose exec php-app bin/cake migrations migrate

