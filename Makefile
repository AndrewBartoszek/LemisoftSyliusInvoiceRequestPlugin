DOCKER_COMPOSE = docker compose
EXEC_APP       = $(DOCKER_COMPOSE) exec -T app
RUN_APP        = $(DOCKER_COMPOSE) run --rm --no-deps app
COMPOSER       = composer
CONSOLE        = ./tests/Application/bin/console
PHPSTAN        = ./vendor/bin/phpstan
PHPCS          = ./vendor/bin/phpcs
PHPCBF         = ./vendor/bin/phpcbf
PHPMND         = ./vendor/bin/phpmnd
PHPUNIT        = ./vendor/bin/phpunit
ECS            = ./vendor/bin/ecs
PSALM          = ./vendor/bin/psalm
BEHAT          = ./vendor/bin/behat
PHPSPEC        = ./vendor/bin/phpspec

nd:
	$(eval EXEC_APP := )
rn:
	$(eval EXEC_APP := $(RUN_APP))

phpcs:
	$(EXEC_APP) $(PHPCS) -p

ecs:
	$(EXEC_APP) $(ECS) check

phpcbf:
	$(EXEC_APP) $(PHPCBF)

phpstan:
	$(EXEC_APP) $(PHPSTAN) --memory-limit=-1

phpmnd:
	$(EXEC_APP) $(PHPMND) src --ignore-funcs=sleep --progress --extensions=all

psalm:
	$(EXEC_APP) $(PSALM)

behat:
	APP_ENV=test $(EXEC_APP) $(BEHAT) --colors --strict --no-interaction -f progress

phpunit:
	$(EXEC_APP) $(PHPUNIT) --testdox --colors=never

backend-packages-install:
	$(EXEC_APP) $(COMPOSER) install --no-interaction --no-scripts

frontend-packages-install:
	$(EXEC_APP) yarn install --cwd tests/Application --pure-lockfile

backend-init:
	$(EXEC_APP) $(CONSOLE) sylius:install --no-interaction
	$(EXEC_APP) $(CONSOLE) sylius:fixtures:load default --no-interaction

frontend-init:
	GULP_ENV=prod  $(EXEC_APP) yarn --cwd tests/Application build

init: backend-packages-install backend-init frontend-packages-install frontend-init

tests: phpunit behat

quality: phpcs phpstan phpmnd psalm ecs
