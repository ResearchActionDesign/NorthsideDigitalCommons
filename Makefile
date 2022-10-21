prepare-site:
	cp htdocs/.htaccess.example htdocs/.htaccess
	cp htdocs/db.ini.example htdocs/db.ini
	cp htdocs/application/config/config.ini.example htdocs/application/config/config.ini
	touch htdocs/application/logs/errors.log
	chmod uga+rw htdocs/application/logs/errors.log
