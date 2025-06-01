.PHONY: setup
setup:
	cp .env.example .env
	docker build . -f docker/dev.Dockerfile -t hyperf-dev-server --no-cache
	docker run --rm -v ".:/opt/www" hyperf-dev-server composer install

.PHONY: up
start:
	docker compose up -d

.PHONY: stop
stop:
	docker compose stop

.PHONY: reload
restart:
	docker compose stop
	docker compose up -d

.PHONY: reload
rebuild:
	docker compose up -d --build

.PHONY: migrate
migrate:
	docker compose exec hyperf-skeleton bin/hyperf.php migrate

.PHONY: seed
seed:
	docker compose exec hyperf-skeleton bin/hyperf.php db:seed

.PHONY: logs
logs:
	docker compose logs -f
