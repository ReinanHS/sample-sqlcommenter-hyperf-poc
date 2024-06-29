up:
	docker compose up
down:
	docker compose down
php:
	docker exec -it sample-sqlcommenter-hyperf-poc bash
build:
	docker build --no-cache -t reinanhs/sample-sqlcommenter-hyperf-poc:benchmarking .