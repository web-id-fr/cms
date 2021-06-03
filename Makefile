#!make

# TESTS
testing:
	./bin/local-php-security-checker-macos
	./vendor/bin/phpcs --report=full
	./vendor/bin/phpstan analyse -c phpstan.neon
	./vendor/bin/phpunit --no-coverage

phpcs:
	./vendor/bin/phpcs --report=full

stan:
	./vendor/bin/phpstan analyse -c phpstan.neon

security:
	./bin/local-php-security-checker-macos

coverage:
	./vendor/bin/phpunit --coverage-html code_coverage
