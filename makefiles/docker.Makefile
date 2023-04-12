.PHONY: docker/exec-sh
## Run shell in docker
docker/exec-sh:
	docker compose run --rm php sh