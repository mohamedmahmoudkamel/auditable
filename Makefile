build:
	docker build -t mkamel/auditable:1.0 .
	docker run --rm -v $(shell pwd):/auditable mkamel/auditable:1.0 composer install --prefer-dist

test:
	docker run --rm -v $(shell pwd):/auditable mkamel/auditable:1.0 ./vendor/bin/phpunit --testdox

clean:
	docker ps -a | awk '{ print $$1,$$2 }' | grep mkamel/auditable:1.0 | awk '{print $$1 }' | xargs -I {} docker rm {}
	docker rmi mkamel/auditable:1.0