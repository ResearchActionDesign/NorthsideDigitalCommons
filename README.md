# MCJC Oral History Trust Website

based on Omeka version 2.5.1

## Local set-up

* Install docker-sync.
* Put a version of the files directory in `htdocs/files`
* Put a most recent DB dump SQL file in the `db` directory -- database container will automatically load whatever `.sql` file is in this directory
* Either run `docker-sync start` in a separate terminal window and then run `make up`, or run `docker-sync-stack start` to do both steps
* To persist DB, run `make export-db` (this will create a new `db-dump.sql` based on the current state of the database)

## Theme development

The MCJC Oral History Trust website uses the Berlin MCJC theme.

### Working with SCSS

SCSS files are located in `css/sass/`. To avoid editing Berlin base theme SCSS
files, we have added custom SCSS partials that mimic the style of the base theme
files.

Styles for all screen sizes are located in `_screen` and `_screen-custom`.
Styles for screens at 768px and smaller are located in `_768max` and
`_768max-custom`. Custom variables are located in `_base-custom`.

To compile SCSS into CSS, install [Compass](http://compass-style.org/install/)
globally, then run `compass compile` in the `css` directory. To watch SCSS files
for changes during development, run `compass watch`.

### Template files

Omeka template files can be customized by copying them to the corresponding
directory within the theme. For example, to customize
`htdocs/application/views/scripts/items/show.php`, copy the file into
`htdocs/themes/berlin_mcjc/items`. The file located in the theme will
override the original.

### JavaScript

The [Lity](https://sorgalla.com/lity/) library is used to enable lightbox
overlays for image files. Lity was used in place of Lightbox because Lightbox
does not support PDFs. The library is included on item show pages via the
custom template located in `items/show.php` (relative to the theme).