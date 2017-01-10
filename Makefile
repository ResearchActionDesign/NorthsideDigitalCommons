up:
	docker-compose up -d

down:
	docker-compose down

export-db:
	docker-compose exec db sh -c 'exec mysqldump --all-databases -uroot -proot' > ./db/db-dump.sql
