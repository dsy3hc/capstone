#Installation Instructions

##Prerequisites
These instructions assume that you already have Apache, PHP, and MySQL/phpMyAdmin installed. Ensure that the following PHP extensions are installed:
* mysql
* mcrypt
* intl

You will also need to install [git](http://git-scm.com/downloads) and [composer](https://getcomposer.org/download/).

##Download Files
An SSH key is required for authorization to download the source code for StarPort. Follow these steps for [generating an SSH key](https://help.github.com/articles/generating-ssh-keys/) for the server, then email the public key to **jaunt@virginia.edu** so that we can grant access permissions for that key. At this point we will also send you a file called `secret.php` which will be used later.

Once we have added the key, you can proceed with setup. `cd` into the document root of your server, then run `git clone https://github.com/uva-slp/jaunt`. This will download the source code and place it into the current directory.

##Configuration
There are a few modifications that you will need to make before the server will run properly.

####Composer
First, run `composer install` from the root project directory.

####htaccess
Next, add two `.htaccess` files: one in the root directory, and one in webroot. The content for each of these files is below.

**.htaccess**
```
<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteRule    ^$    webroot/    [L]
    RewriteRule    (.*) webroot/$1    [L]
    RewriteBase /jaunt/
</IfModule>
```

**webroot/.htaccess**
```
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
    RewriteBase /jaunt/
</IfModule>
```
If you did not run `git clone` from the document root of the server, then you may require a different `RewriteBase`. See the [Apache docs](http://httpd.apache.org/docs/current/mod/mod_rewrite.html#rewritebase) for more information on RewriteBase.

####Database
Before establishing the database, you need to establish a password for the StarPort database account. We recommend [generating a secure password](https://identitysafe.norton.com/password-generator/) which is unique to the system, but you can use an existing password if you prefer.

Log in to phpMyAdmin as the `root` user. Copy the SQL code from `schema.sql` and paste it into the SQL tab. Before clicking the Go button, modify the following lines of SQL:
```
GRANT ALL ON slp_jaunt.* TO 'jaunt' IDENTIFIED BY '';
GRANT ALL ON slp_jaunt.* TO 'jaunt'@'localhost' IDENTIFIED BY '';
```
If your password was `1234`, you would change the lines to 
```
GRANT ALL ON slp_jaunt.* TO 'jaunt' IDENTIFIED BY '1234';
GRANT ALL ON slp_jaunt.* TO 'jaunt'@'localhost' IDENTIFIED BY '1234';
```
Now click Go. This will create and initialize the database. The initialized database will contain a default user for each user type.The usernames for these accounts are (usertype)@ridejaunt.org - for example, **client@ridejaunt.org** or **scheduler@ridejaunt.org**. The admin account can be used to create more staff users. The password for all of these example accounts is simply `password`, so you should login and change the password as soon as possible.

You will also need to update `config/app.php` to include the password you set up earlier.
```
'Datasources' => [
    'default' => [
        'className' => 'Cake\Database\Connection',
        'driver' => 'Cake\Database\Driver\Mysql',
        'persistent' => false,
        'host' => 'localhost',
        'username' => 'jaunt',
        'password' => '',
        'database' => 'slp_jaunt',
        'prefix' => false,
        'encoding' => 'utf8',
        'timezone' => 'UTC',
        'cacheMetadata' => true,
```
Replace the empty `''` in `'password' => ''` with the new password (line 211 of the specified file).
```
'Datasources' => [
    'default' => [
        'className' => 'Cake\Database\Connection',
        'driver' => 'Cake\Database\Driver\Mysql',
        'persistent' => false,
        'host' => 'localhost',
        'username' => 'jaunt',
        'password' => '1234',
        'database' => 'slp_jaunt',
        'prefix' => false,
        'encoding' => 'utf8',
        'timezone' => 'UTC',
        'cacheMetadata' => true,
```
#### Installer
Double click on `installer.cmd` to run an installer which will set up a script that runs every morning at 1:00 am to check for users with expiring CAT Disability numbers.

#### reCAPTCHA
Take the `secret.php` file that you received earlier and place it in the `config` folder.

#### dompdf
Included in the root directory of the source code is a zip folder called `dompdf.zip`. Unzip this folder and place it inside the vendor folder. Do not modify the directory structure of the unzipped folder.
