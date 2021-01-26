#!make

# TESTS
testing:
	- ./vendor/bin/security-checker security:check
	- ./vendor/bin/phpcs --report=full
	- ./vendor/bin/psalm
	- ./vendor/bin/phpunit --no-coverage

coverage:
	- ./vendor/bin/phpunit --coverage-html code_coverage
