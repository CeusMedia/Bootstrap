
# --  COMPOSER  -----------------------------------------------------------
composer-install-dev:
	@composer install

composer-install-nodev:
	@composer install --no-dev

composer-update-dev:
	@composer update

composer-update-nodev:
	@composer update --no--dev


# --  DEV: TESTS  ---------------------------------------------------------
test-syntax:
	@echo "Checking syntax..."
	@find src -type f -print0 | xargs -0 -n1 xargs php -l

dev-test-unit:	#composer-install-dev
	@./vendor/bin/phpunit test

dev-phpstan:
	@vendor/bin/phpstan analyse --configuration phpstan.neon --xdebug || true

dev-phpstan-save-baseline:
	@vendor/bin/phpstan analyse --configuration phpstan.neon --generate-baseline phpstan-baseline.neon || true


# --  DEV: Docs  ---------------------------------------------------------
dev-create-docs: composer-install-dev composer-update-dev
	@rm -Rf doc
	@php vendor/ceus-media/doc-creator/doc-creator.php --config-file=doc-creator.xml


# --  GIT  ----------------------------------------------------------------
git-show-status:
	@git status

git-show-changes:
	@git diff

git-update:
	@git fetch
	@git pull
