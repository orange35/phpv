# Automatic PHP CLI Version 

![](orange35-phpv-usage.gif)

**orange35/phpv** is a php wrapper which replaces the **php** command and executes a PHP CLI version specified in the one of the following files:

- .htaccess_dev ***1**
- .htaccess
- .phpv

**For example:**

When you run the following command
    
    php -v

PHP Wrapper searches for first of following files:

- ./.phpv
- ./.htaccess_dev
- ./.htaccess
- ./public/.htaccess_dev
- ./public/.htaccess
- ./www/.htaccess_dev
- ./www/.htaccess
- ./public_html/.htaccess_dev
- ./public_html/.htaccess

If it is either a .htaccess_dev or .htaccess file it is looks for a string like:

    SetHandler proxy:fcgi://php71

or
    
    AddHandler application/x-httpd-php71 .php
    
if it is a .phpv file the wrapper reads its content which will be a cli command name e.g.:

    php71
    
and executes `php71 -v` instead of `php -v`

The commands like **php71**, **php72** and so on must be available in your system. If not, please see the installation notes #4-6 below.

If there is no PHP version specified in the .phpv, .htaccess_dev or .htaccess file then either:
- a version specified in the PHPV_DEFAULT env variable will be used (if exists)
- a default php version of your system will be used (if exists), e.g.: /usr/bin/php
- the **PHPV: can't find PHP CLI** error message will be thrown

----
## Installation

1. Install [composer](https://getcomposer.org/download/) if not available

2. Install the package

        composer global require orange35/phpv

3. If there are already commands like **php71**, **php72** and so on in your system then skip the steps 4-6. 

4. Make sure the PATHs order is the following in your `~/.bashrc` or `~/.profile`:

        PATH=$HOME/bin:$HOME/.composer/vendor/bin:$PATH

5. Logout your bash/SSH console and log in again to execute new changes in the `~/.bashrc` or `~/.profile`.

6. Create symlinks in the `~/bin` to different php version, e.g:

        php70 -> /opt/php7.0/bin/php
        php71 -> /opt/php7.1/bin/php
        php72 -> /opt/php7.2/bin/php
        php73 -> /opt/php7.3/bin/php
        php74 -> /opt/php7.4/bin/php

## Usage

1. go to your project
    
        cd ~/public_html/example.com
        
2. check what is php version
    
        $ php -v
        PHP 7.0.33 (cli) ...
    
2.  create an .htaccess_dev file if doesn't exist

        cp .htaccess .htaccess_dev
            
3. specify a PHP version in the .htaccess_dev file 

        # The next line affects the Apache +  mod_suphp configuration 
        AddHandler application/x-httpd-php73 .php
        #
        # The next lines affects the Apache + php-fpm configuration
        <IfModule !mod_suphp.c>
            <FilesMatch \.php$>
                SetHandler proxy:fcgi://php73
            </FilesMatch>
        </IfModule>
    
4.  check what is php version now

        $ php -v
        PHP 7.3.9 (cli) ...

## Notes
***1** - The **.htaccess_dev** file may be used (if exists) instead of .htaccess if you have the following Apache configuration:
    
        AccessFileName .htaccess_dev .htaccess
It is very convenient to have a development version of .htaccess file which doesn't affect the production version.
