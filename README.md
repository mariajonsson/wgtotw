wgtotw
=========

A PHP-based and MVC-inspired (micro) framework / webbtemplate / boilerplate for a website forum.

Wgtotw is built using Anax-MVC as base. Anax-MVC has been extended with database support (cdatabase module), forms (cform module), content management and users.

Read article about Anax-MVC here: ["Anax som MVC-ramverk"](http://dbwebb.se/kunskap/anax-som-mvc-ramverk) and here ["Bygg en me-sida med Anax-MVC"](http://dbwebb.se/kunskap/bygg-en-me-sida-med-anax-mvc).

Wgtotw is by Maria Jonsson.
Anax-MVC by Mikael Roos, me@mikaelroos.se.

Install and setup
-------------------

Clone repository from GitHub.
The webroot folder is the web page's root folder.

### Settings

Some files need to be modified in order for the website to work.

* webroot/.htaccess 
  Settings for clean URLs. #Rewrite module must be on for clean urls to work. Change base url in #Rewrite base if necessary.
* app/config/config_mysql.php
  Change the settings for MySQL. This file is referred to from webroot/index.php

### Setup



License
------------------

This software is free software and carries a MIT license.


Use of external libraries
-----------------------------------

The following external modules are included and subject to its own license.



### Modernizr
* Website: http://modernizr.com/
* Version: 2.6.2
* License: MIT license
* Path: included in `webroot/js/modernizr.js`



### PHP Markdown
* Website: http://michelf.ca/projects/php-markdown/
* Version: 1.4.0, November 29, 2013
* License: PHP Markdown Lib Copyright © 2004-2013 Michel Fortin http://michelf.ca/
* Path: included in `3pp/php-markdown`




History
-----------------------------------


###History for wgtotw

v1.0 (latest)


