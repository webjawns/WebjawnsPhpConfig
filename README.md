Webjawns PHP Configuration
==========================

Webjawns PHP Configuration (http://webjawns.com) is a Zend Framework 2 module allowing global, per controller, and per route configuration of `php.ini` options using a standard configuration file.  For example, developers can set a global memory limit, while increasing or decreasing it to meet a particular route or controller's requirements.  The same applies to any other INI option (e.g. max_execution_time, max_input_vars, etc.).

Priority
========
If the same INI option is defined for a route and controller, as well as globally, the route option takes precedence.  If the same INI option is defined globally and for a controller, the controller option takes precedence.  The priorities are as follows, with the first position having precedence:

1. Route
2. Controller
3. Global

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
           
           'display_errors'     => '1',
           'date.timezone'      => 'UTC',
           'max_execution_time' => '15',
           'memory_limit'       => '16M',
           
           'controllers' => array(
               'Application\Controller\Index' => array(
                   'memory_limit' => '64M',
               ),
           ),
           
           'routes' => array(
               'home' => array(
                   'memory_limit'       => '32M',
                   'max_execution_time' => '30',
               ),
           ),
       ),
   );
