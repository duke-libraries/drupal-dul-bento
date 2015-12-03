drupal-dul-bento
================

This is a custom module for drupal 7 developed by Duke University Libraries (using some code adapted from UNC and NCSU Libraries)


File structure
---------------------
- the main page template file is bento.tpl.php
  - this controls the layout of the bento home page
  - the bento sections are loaded in via includes

- the assets folder houses css, js, images, and included files


Using the module
---------------------

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

- Logging options
  - log files are generated in your 'private' drupal directory as 'bento_log.txt'
  - the daily option creates a new file appended with the date

- 'other' lists
  - these lists are used to generate the search strings for the 'other' bento search box results (content types, libraries)
