up:
	docker compose up
down:
	docker compose down
php:
	docker exec -it sample-sqlcommenter-hyperf-poc bash
build:
	docker build --no-cache -t reinanhs/sample-sqlcommenter-hyperf-poc:benchmarking .
push:
	docker push reinanhs/sample-sqlcommenter-hyperf-poc:benchmarking
gbuild:
	gcloud builds submit . --config=cloudbuild.yaml --substitutions=REPO_NAME=sample-sqlcommenter-hyperf-poc,TAG_NAME=0.0.7