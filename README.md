# JAUNT
A CakePHP application built for [JAUNT Inc](http://www.ridejaunt.org) which provides real-time bus tracking information in a customer-oriented web portal.

![CirclCI Build Status](https://circleci.com/gh/uva-slp/jaunt.png?circle-token=3464093d197f7948542f23f6554b58935dee90b0)

## Local Development Setup

### Install LAMP Stack
If you didn't setup the LAMP stack when doing the CakePHP tutorial.

[Setup Apache/MySQL/PHP](https://help.ubuntu.com/community/ApacheMySQLPHP)

### Configure local MySQL Database
`mysql -u root -p`

Enter the password for your root MySQL account. Now run the following:

`CREATE DATABASE slp_jaunt;`

`GRANT ALL ON slp_jaunt.* to 'jaunt' identified by '';`

`GRANT ALL ON slp_jaunt.* to 'jaunt'@'localhost' identified by '';`

`CREATE DATABASE slp_jaunt_test;`

`GRANT ALL ON slp_jaunt_test.* to 'jaunt' identified by '';`

`GRANT ALL ON slp_jaunt_test.* to 'jaunt'@'localhost' identified by '';`

### Clone the Repository
`cd` into the Document Root of your Apache server which is likely `/var/www/html/`.

Clone the repo

`git clone https://github.com/uva-slp/jaunt.git`

### Configure CakePHP

Look over the [tutorial](http://aaronbloomfield.github.io/slp/docs/cakephp-getting-started.html) from the CakePHP homework.
