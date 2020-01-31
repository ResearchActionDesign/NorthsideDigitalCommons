up:
	docker-compose up -d

down:
	docker-compose down

export-db:
    # tail -n +2 command is needed here to suppress first line of output from mysqldump which is a password error.
	docker-compose exec db sh -c 'exec mysqldump --all-databases -uroot -proot' | tail -n +2 > ./db/db-dump.sql
