#!make

# TESTS
testing:
	- ./vendor/bin/security-checker security:check
	- ./vendor/bin/phpcs --report=full
	- ./vendor/bin/psalm
	- ./vendor/bin/phpunit --no-coverage
