prepare-site:
	cp htdocs/.htaccess.example htdocs/.htaccess
	cp htdocs/db.ini.example htdocs/db.ini
	cp htdocs/application/config/config.ini.example htdocs/application/config/config.ini
	touch htdocs/application/logs/errors.log
	chmod uga+rw htdocs/application/logs/errors.log

up:
	docker-sync-stack start

down:
	docker-compose down

export-db:
    # tail -n +2 command is needed here to suppress first line of output from mysqldump which is a password error.
	docker-compose exec db sh -c 'exec mysqldump --all-databases -uroot -proot' | tail -n +2 > ./db/db-dump.sql
