# MCJC Oral History Trust Website

based on Omeka version 2.5.1

## Local set-up

* Put a version of the files directory in `htdocs/files`
* Put a most recent DB dump SQL file in the `db` directory -- database container will automatically load whatever `.sql` file is in this directory
* Run `make up`
* To persist DB, run `make export-db` (this will create a new `db-dump.sql` based on the current state of the database)

## Required plug-ins

The following plugins are required:
* Archive Repertory
* Clean Url - using a schema of `/collections/collection-identifier/item-identifier`
* COinS
* CSVExport
* CSV Import
* Derivative Images
* Element Types
* Exhibit Builder
* HTML5 Media
* Item Relations
* Simple Pages
* Simple Vocab
* Taxonomy

## Custom plug-ins

The `MCJCDeployment` plug-in serves as an all-purpose container for overrides and database migrations, and also enables Rollbar error notifications.
Some of the migrations tweak aspects of the default Omeka field set-up, so if you're starting a fresh install of this codebase
you'll need to force the migrations to run by manually setting the `MCJCDeployment` version number to `2.1.0`.

The plugin also provides a few custom view handlers and overrides which are needed for the `berlin_mcjc` theme to function; these are:

* `ExhibitAttachment.php` - completely copies the `plugins/ExhibitBuilder/helpers/ExhibitAttachment.php` code except for forcing the `$forceImage` parameter to always have a value of `false` so that in-line HTML5 players display on exhibit pages.
This view handler overrides the `ExhibitBuilder` one purely by virtue of the fact that the Zend view handler stack is last-in, first-out, so the `MCJCDeployment` plugin views handler directory is searched before `ExhibitBuilder`.
* `McjcFileMarkup.php` - copies the default file markup handler except for customizing the behavior of tape log, transcript, and abstract PDFs. This handler is called explicitly in the `berlin_mcjc` theme code.

## Rollbar error handling

The MCJCDeployment plugin provides Rollbar error notification functionality by default; in order for this to work the option `log.rollbar_access_token` needs to be set to
a valid access token inside `application/config.ini`.

## Berlin_mcjc theme 

The MCJC Oral History Trust website uses the Berlin MCJC theme.

### Custom theme functions

The theme `custom.php` contains a number of custom functions which are used in the theme templates. Functions are documented in-line and generally prefixed with `mcjc_`.
Some of these functions largely copy code from the Omeka core functions, so if errors appear after the Omeka version is updated check here to see if the core function code changes need to be propagated.

### Working with SCSS

SCSS files are located in `css/sass/`. To avoid editing Berlin base theme SCSS
files, we have added custom SCSS partials that mimic the style of the base theme
files.

Styles for all screen sizes are located in `_screen` and `_screen-custom`.
Styles for screens at 768px and smaller are located in `_768max` and
`_768max-custom`. Custom variables are located in `_base-custom`.

The `docker-compose` file contains configuration for running Compass in a container
automatically on local dev. See readme in the `berlin_mcjc` theme directory for manual info.
Production compass config is located in `themes/berlin_mcjc/config.rb`.

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

## Deployment

The `.circleci/config.yml` file currently auto-deploys develop branch to [staging](https://archives-new.jacksoncenter.info)
and production to [archives.jacksoncenter.info](https://archives.jacksoncenter.info).
