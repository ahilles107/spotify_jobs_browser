Browse Spotify Jobs from Your terminal
======================================

This project is an PHP (7.4) console command application 
allowing You to browse spotify jobs offers in Sweeden. 

It's designed to allow you easly add new jobs provider: 
with new command and specifit to company `JobsProvider` class.

Requirements:

 * php >= 7.4
 * php-ctype
 * php-iconv
 * php-json
 * composer
 
Installation:
 
  * `composer install`

Usage:

Run `php bin/console jobs:spotify` to fetch latest jobs from 
Spotify webiste (API endpoint). 

When command will show You list with jobs, pick single job (by it's ID)
to see it's detailed description. 

## Usage with docker
 
Requirements:

 * docker
 * docker-compose
 
Installation:
  
  * `cd docker`
  * `docker-compose build`
  * `docker-compose run phpfpm composer install`
  * `docker-compose run phpfpm bin/console jobs:spotify`
  * `docker-compose run phpfpm bin/console jobs:spotify --disable-cache` - to discard cache
