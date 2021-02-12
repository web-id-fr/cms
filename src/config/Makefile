#!make
include .env
export $(shell sed 's/=.*//' .env)

# INSTALLS
update: composer.lock package-lock.json public/js/vendor.js
	make migrate

install: .env vendor node_modules public/js/vendor.js public/storage active_modules
	php artisan key:generate

install_db:
	echo "CREATE DATABASE IF NOT EXISTS $(DB_DATABASE)" | mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) -h$(DB_HOST)
	echo "CREATE DATABASE IF NOT EXISTS $(DB_DATABASE_TEST)" | mysql -u$(DB_USERNAME_TEST) -p$(DB_PASSWORD_TEST) -h$(DB_HOST)
	make fresh
	php artisan db:seed --class="Webid\Cms\Database\Seeders\DatabaseSeeder"

# TESTS
testing:
	./vendor/bin/phpcs --report=full
	./vendor/bin/phpstan analyse --memory-limit=2G

# lance un Code Sniffer
cssee:
	./vendor/bin/phpcs --report=full

# Fixe les errors + warnings du Code Sniffer
csfix:
	./vendor/bin/phpcbf -w

stan:
	./vendor/bin/phpstan analyse --memory-limit=2G

# COMMANDES ARTISAN
migrate:
	php artisan migrate
	php artisan migrate --database mysql_test

rollback:
	php artisan migrate:rollback
	php artisan migrate:rollback --database mysql_test

refresh:
	rm -rf storage/app/folders
	php artisan migrate:refresh --seed
	php artisan migrate:refresh --database mysql_test

fresh:
	rm -rf storage/app/folders
	php artisan migrate:fresh --seed
	php artisan migrate:fresh --database mysql_test

# DEPENDANCES
.env:
	cp .env.example .env

node_modules:
	npm install

public/js/vendor.js:
	npm run dev

public/storage:
	php artisan storage:link

vendor:
	composer install

composer.lock:
	composer install

package-lock.json:
	npm install

# MODULES
active_modules:
	php artisan module:enable
