# Решение тестового задания
Реализация базового api на ларавел

## Поднятие окружения
### Требования
- Docker, docker-compose
- Make (не обязательно)

### Особенности и папки
- .env с конфигами для приложения находится в корне проекта
- APP_HOST_PORT параметр для внешнего порта по умалчанию (8000)
Папки:
- src/ - корень приложения
- docker/ - конфиги образов
- .data/ - данные контейнеров(postgresql,redis)

### Первый запуск
- В корне проекта ввести:
- `make startup` -  сборка и запуск контейнеров, установка пакетов, генерация ключа
    
    Если отсутсвует `make`, нужно поочередно запустить следующие команды:
	```sh
    docker-compose up -d;
	docker-compose exec -i php composer install;
	docker-compose exec -i php php artisan key:generate --ansi;
	docker-compose exec -i php php artisan migrate;
    ```
- Для тестировния функционала: `make test`

Остановка проекта: `make down`
Последующий запуск: `make up`
Запуск тестов: `make test`
Подключиться к shell: `make shell`