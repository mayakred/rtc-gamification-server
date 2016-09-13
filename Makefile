THIS_FILE := $(lastword $(MAKEFILE_LIST))

cs:
	php-cs-fixer fix --verbose

cs-dry-run:
	bin/php-cs-fixer fix --verbose --dry-run

c-inst:
	composer install

test:
	bin/phpunit -c app src

stage: set-permissions cache-dev cache-test migrations-dev fixtures-dev migrations-test fixtures-test install-assets
	$(MAKE) -f $(THIS_FILE) set-permissions

dev: set-permissions cache-dev install-assets
	$(MAKE) -f $(THIS_FILE) set-permissions

prod: set-permissions cache-prod dump install-assets
	$(MAKE) -f $(THIS_FILE) set-permissions

set-permissions:
	sudo chmod -R ug+rw .
	sudo chmod -R a+rw var/cache var/logs

migrations-dev:
	php bin/console doctrine:migrations:migrate --env=dev

migrations-test:
	php bin/console doctrine:migrations:migrate --env=test

fixtures-dev:
	php bin/console h:d:f:l --env=dev

clear-cache-dev: set-permissions cache-dev
	$(MAKE) -f $(THIS_FILE) set-permissions

clear-cache-prod: set-permissions cache-prod
	$(MAKE) -f $(THIS_FILE) set-permissions

cache-dev:
	php bin/console cache:clear --env=dev

cache-prod:
	php bin/console cache:clear --env=prod --no-debug

cache-test:
	php bin/console cache:clear --env=test

update-db:
	php bin/console doctrine:schema:update --force --dump-sql

reload-db:
	php bin/console doctrine:database:drop --force
	php bin/console doctrine:database:create
	php bin/console doctrine:schema:create

load-fixtures:
	php bin/console doctrine:fixtures:load

dump:
	composer dump-autoload --optimize
	php bin/console assetic:dump --env=prod --no-debug

install-assets: install-web-assets

install-web-assets:
	php bin/console assets:install web --symlink
