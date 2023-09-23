.PHONY: startup
startup:
	@docker-compose up -d
	@docker-compose exec -i php composer install
	@docker-compose exec -i php php artisan key:generate --ansi

.PHONY: up
up:
	@docker-compose up -d

.PHONY: down
down:
	@docker-compose down

.PHONY: restart
restart:
	@docker-compose down; 
	@docker-compose up -d

.PHONY: build
build:
	@docker-compose build

.PHONY: shell
shell:
	@docker-compose exec -it php sh

.PHONY: test
test:
	@docker-compose exec -i php php artisan test

.PHONY: key-gen
key-gen:
	@docker-compose exec -i php php artisan key:generate --ansi