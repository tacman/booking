init:
	@docker-compose up -d
	@docker-compose exec fpm composer install --ignore-platform-reqs --no-ansi --no-interaction

test:
	@docker exec booking make run-tests

run-tests:
	./vendor/bin/phpunit
	./vendor/bin/behat -p booking --format=progress -v
