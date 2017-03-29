MCJC Oral History Trust Website

based on Omeka version 2.5.1

# Local set-up

* Install docker-sync.
* Put a version of the files directory in `htdocs/files`
* Put a most recent DB dump SQL file in the `db` directory -- database container will automatically load whatever `.sql` file is in this directory
* Either run `docker-sync start` in a separate terminal window and then run `make up`, or run `docker-sync-stack start` to do both steps
* To persist DB, run `make export-db` (this will create a new `db-dump.sql` based on the current state of the database)
