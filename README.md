drupal-dul-bento
================

This is a custom module for drupal 7 developed by Duke University Libraries (using some code adapted from UNC and NCSU Libraries)


File structure
-----------------------------
- the main page template file is bento.tpl.php
  - this controls the layout of the bento home page
  - the bento sections are loaded in via includes

- the assets folder houses css, js, images, and included files



Managing CSS and JS Assets
-----------------------------
- CSS and JS files live in their appropriate sub folders. When editing scripts.js or styles.css, you will need to manually minify them (using something like minifier.org) and update the .min versions as well. These minified versions are called in dul_bento.module, and are in turn concatenated into the drupal assets stack. Note that google_search.js remains a discrete file and is loaded by itself as an external script in drupal.



Using the module
-----------------------------

Installing and enabling the module will add a config menu at /admin/config/bento

Options include:

- Bento Page Title
  - Sets the drupal page title for your bento page

- Bento URL
  - sets the url path for your bento page (don't use init or trailing slashes!)

- Summon Access ID
  - for example, we use 'duke'

- Summon Secret Key
  - your secret key

- Summon Client Key
  - your client key

- Indexing option
  - by default, the results page is rendered with a robots metatag set to 'noindex'
  - you can tick the checkbox to turn this off (in case you'd like the page to be indexed)

- Logging options
  - log files are generated in your 'private' drupal directory as 'bento_log.txt'
  - the daily option creates a new file appended with the date

- 'other' lists
  - these lists are used to generate the search strings for the 'other' bento search box results (content types, libraries)
