.PHONY: code/cs
## CS fix
code/cs:
	$(BIN)/php-cs-fixer fix --verbose

 .PHONY: code/phpstan
## Run phpstan
code/phpstan:
	$(BIN)/phpstan analyse

.PHONY: code/cs-dry-run
## CS check
code/cs-dry-run:
	$(BIN)/php-cs-fixer fix --verbose --dry-run

.PHONY: code/check
## Code check
code/check: code/cs-dry-run code/phpstan

.PHONY: code/test
## Run PHPUnit tests
code/test:
	$(BIN)/phpunit
