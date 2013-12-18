Webjawns PHP Configuration
==========================

Webjawns PHP Configuration (http://webjawns.com) is a Zend Framework 2 module allowing configuration of `php.ini` options 
with a standard configuration file.

Installation
============

1. Install the module via Composer by running:

   ```sh
   php composer.phar require webjawns/webjawns-php-config:dev-master
   ```
   or download it directly from GitHub and copy it to your application's `module/` directory.
2. Add the `WebjawnsPhpConfig` module to the modules section of `config/application.config.php`.
3. Create or edit an autoloaded configuration file and customize your `php.ini` options.
   ```
   return array(
       'webjawns_php_config' => array(
           // Whether to throw a RuntimeException if ini_set() returns false
           'throw_exception_on_failure' => true,
           
           'display_errors' => '1',
           'date.timezone'  => 'UTC',
       ),
   );
