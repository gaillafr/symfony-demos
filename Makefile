-include .env

DOCKER_COMPOSE = docker-compose
EXEC = $(DOCKER_COMPOSE) exec
EXEC_PHP = $(DOCKER_COMPOSE) exec app
SYMFONY = $(EXEC_PHP) bin/console
COMPOSER = $(EXEC_PHP) composer

##
## Project
## -------
##

build:
	$(DOCKER_COMPOSE) build

kill:
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --volumes --remove-orphans

install: ## Install and start the project
install: build start vendor db

reset: ## Stop and start a fresh install of the project
reset: kill install

start: ## Start the project
	$(DOCKER_COMPOSE) up -d --remove-orphans --force-recreate

stop: ## Stop the project
	$(DOCKER_COMPOSE) stop

clean: ## Stop the project and remove generated files
clean: kill
	rm -rf vendor

no-docker:
	$(eval DOCKER_COMPOSE := \#)
	$(eval EXEC_PHP := )
	$(eval EXEC_JS := )

.PHONY: build kill install reset start stop clean no-docker

##
## Utils
## -----
##

wait-for-db:
	@$(EXEC_PHP) php -r "set_time_limit(60);for(;;){if(@fsockopen('mariadb',3306)){break;}echo \"Waiting for MySQL\n\";sleep(1);}"

db: ## Reset the database
db: vendor wait-for-db
	$(SYMFONY) doctrine:database:drop --if-exists --force
	$(SYMFONY) doctrine:database:create --if-not-exists
	$(SYMFONY) doctrine:migrations:migrate --no-interaction --allow-no-migration

migration: ## Generate a new doctrine migration
migration: vendor
	$(SYMFONY) doctrine:migrations:diff

db-validate-schema: ## Validate the doctrine ORM mapping
db-validate-schema: vendor
	$(SYMFONY) doctrine:schema:validate

schema: ## Update database schema
schema:
	$(SYMFONY) doctrine:schema:update --force

fixtures: ## Install project's fixtures
fixtures:
	$(SYMFONY) hautelook:fixtures:load -n

.PHONY: db fixtures migration schema vendor

vendor: composer.lock
	$(COMPOSER) install

##
## Quality assurance
## -----------------
##

ci: ## Run all quality insurance checks (tests, code styles, linting, security, static analysis...)
ci: php-cs-fixer phpcs phpmd phpstan lint validate-composer validate-mapping security test test-coverage

ci.local: ## Run quality insurance checks from inside the php container
ci.local: no-docker ci

lint: ## Run lint check
lint:
	$(SYMFONY) lint:yaml config/ --parse-tags
	$(SYMFONY) lint:yaml translations/
	$(SYMFONY) lint:container

phpcs: ## Run phpcode_sniffer
phpcs:
	$(EXEC_PHP) vendor/bin/phpcs

phpcbf: ## Run phpcode_beautifier
phpcbf:
	$(EXEC_PHP) vendor/bin/phpcbf src/ -v

php-cs-fixer: ## Run PHP-CS-FIXER
php-cs-fixer:
	$(EXEC_PHP) vendor/bin/php-cs-fixer fix --verbose

php-cs-fixer.dry-run: ## Run php-cs-fixer in dry-run mode
php-cs-fixer.dry-run:
	$(EXEC_PHP) vendor/bin/php-cs-fixer fix --verbose --diff --dry-run

phpmd: ## Run PHPMD
phpmd:
	$(EXEC_PHP) vendor/bin/phpmd src/ ansi phpmd.xml.dist

#phpmnd: ## Run PHPMND
#phpmnd:
#	$(EXEC_PHP) vendor/bin/phpmnd src --extensions=default_parameter

phpstan: ## Run PHPSTAN
phpstan:
	$(EXEC_PHP) vendor/bin/phpstan analyse

rector.dry: ## Dry-run rector
rector.dry:
	$(EXEC_PHP) vendor/bin/rector process --dry-run

rector: ## Run RECTOR
rector:
	$(EXEC_PHP) vendor/bin/rector process

security: ## Run security-checker
security:
	$(EXEC_PHP) bin/security-checker

test: ## Run phpunit tests
test:
	$(EXEC_PHP) vendor/bin/phpunit

test-coverage: ## Run phpunit tests with code coverage (phpdbg)
test-coverage: test-coverage-pcov

test-coverage-phpdbg: ## Run phpunit tests with code coverage (phpdbg)
test-coverage-phpdbg:
	$(EXEC_PHP) phpdbg -qrr ./vendor/bin/phpunit --coverage-html=var/coverage

test-coverage-pcov: ## Run phpunit tests with code coverage (pcov - uncomment extension in dockerfile)
test-coverage-pcov:
	$(EXEC_PHP) vendor/bin/phpunit --coverage-html=var/coverage

test-coverage-xdebug: ## Run phpunit tests with code coverage (xdebug - uncomment extension in dockerfile)
test-coverage-xdebug:
	$(EXEC_PHP) vendor/bin/phpunit --coverage-html=var/coverage

test-coverage-xdebug-filter: ## Run phpunit tests with code coverage (xdebug with filter - uncomment extension in dockerfile)
test-coverage-xdebug-filter:
	$(EXEC_PHP) vendor/bin/phpunit --dump-xdebug-filter var/xdebug-filter.php
	$(EXEC_PHP) vendor/bin/phpunit --prepend var/xdebug-filter.php --coverage-html=var/coverage

validate-composer: ## Validate composer.json and composer.lock
validate-composer:
	$(EXEC_PHP) composer validate
	$(EXEC_PHP) composer normalize --dry-run

validate-mapping: ## Validate doctrine mapping
validate-mapping:
	$(SYMFONY) doctrine:schema:validate --skip-sync -vvv --no-interaction

update-readme: ## Update the README.md file on all branches
update-readme:
	bin/updater README.md

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help
