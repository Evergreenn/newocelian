# Var dirs
CACHE_DIR = var/cache/
LOG_DIR = var/logs/
SESSION_DIR = var/sessions/
MAKEFILE_DIR = $(shell pwd)

# Default value
ENTITY = AppBundle 

# Bin files
CS_FIXER = bin/php-cs-fixer-v2.phar
PHPUNIT = bin/phpunit.phar
CONSOLE = bin/console
BEHAT = bin/behat

help:
	@echo "Some useful operations for Symfony 3"

# Install the project
install: install-db git-install-hooks ai csr

# Install the project for gitlab CI
install-ci: ci-install

# Install database
install-db: ddc dsc dlf

pull:
	git pull
	make ci
	make du
	make ai
	make csr

# Remove cache dir
cc:
	rm -rf $(CACHE_DIR)*

# Warmup cache
cw: cc
	$(CONSOLE) ca:wa --env=dev

# Remove log dir
cl:
	rm -rf $(LOG_DIR)*

# Remove session dir
cs:
	rm -rf $(SESSION_DIR)*

# Clear all
ca: cw cl cs

# Generate doctrine entity
dge:
	$(CONSOLE) do:ge:entity

# Generate setter/getter
dgee:
	$(CONSOLE) do:ge:entities $(ENTITY)

# Update databse
du:
	$(CONSOLE) do:sc:up --force

# Dump SQL query
dud:
	$(CONSOLE) do:sc:up --dump-sql

# Create database
ddc:
	$(CONSOLE) do:da:cr --if-not-exists

# Create schema
dsc:
	$(CONSOLE) do:sc:cr || $(CONSOLE) do:sc:up --force

# Loads fixtures
dlf:
	$(CONSOLE) do:fi:lo --append

# Start Web Server
sr:
	$(CONSOLE) se:ru

# Clear everythings and run web server
csr: ca sr

# Install assets
ai:
	$(CONSOLE) as:in --env=dev

# Run behat tests
be: ca
	$(BEHAT)

# Run phpunit tests
unit:
	phpunit || $(PHPUNIT)

# Run all tests
test : be unit

# Install composer
ci:
	composer install

# Update composer
cu:
	composer update

# Fix Source
fix-src:
	php-cs-fixer -vvv fix src/ || $(CS_FIXER) -vvv fix src/

# Fix Tests
fix-tests:
	php-cs-fixer -vvv fix tests/ || $(CS_FIXER) -vvv fix tests/

# Fix Source and Tests
fix-all: fix-src fix-tests

# CI
ci-clear-cache: cc
	$(CONSOLE) ca:wa --env=test

ci-install-db: install-db

ci-install: ci-install-db ci-clear-cache

ci-be: ci-clear-cache
	$(BEHAT)

# Install git hooks
git-install-hooks: git-install-pre-commit git-install-pre-push

git-install-pre-commit:
	ln -s $(MAKEFILE_DIR)/hooks/git/pre-commit.sh $(MAKEFILE_DIR)/.git/hooks/pre-commit
git-install-pre-push:
	ln -s $(MAKEFILE_DIR)/hooks/git/pre-push.sh $(MAKEFILE_DIR)/.git/hooks/pre-push
