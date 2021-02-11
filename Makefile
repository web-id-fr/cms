#!make

# TESTS
testing:
	./bin/local-php-security-checker-macos
	./vendor/bin/phpcs --report=full
	./vendor/bin/psalm
	./vendor/bin/phpunit --no-coverage

phpcs:
	./vendor/bin/phpcs --report=full

psalm:
	./vendor/bin/psalm

security:
	./bin/local-php-security-checker-macos

coverage:
	./vendor/bin/phpunit --coverage-html code_coverage
