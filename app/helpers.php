<?php

/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

/**
 * Configuration values array
 * 
 * @var array $_config
 */
$_config = null;

/**
 * Initialize database connection
 * 
 * @var App\Core\Database $database
 */
$_database = null;

/**
 * Routes manager instace
 * 
 * @var Core\RoutesManager $routes
 */
$routes = null;

/**
 * Authenticator instance
 * 
 * @var App\Core\Authenticator $authenticator
 */
$_authenticator = null;

/**
 * Language texts array
 * 
 * @var array $_lang
 */
$_lang = null;

/**
 * Load all application components
 */
function load_app()
{
   global $_config, $_database, $routes, $_authenticator, $_lang;

   // Load all application components
   foreach (glob(__DIR__ . '/core/*.php') as $component)
      require_once $component;

   $_config = parse_ini_file(__DIR__ . '/../config.ini');
   
   // Load all application routes
   $routes = new App\Core\RoutesManager;
   require_once __DIR__ . '/routes.php';
   require_once __DIR__ . '/models/Model.php';
   require_once __DIR__ . '/controllers/Controller.php';
   
   // Load language texts
   if (config('app.enable_multi_lang')):
      $lang_file = __DIR__ . '/langs/' . (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : config('app.default_lang')) . '.php';
      if (is_file($lang_file))
         require_once $lang_file;
      else
         die("Cannot load texts from <b>{$lang_file}</b> because file does not exists!");
      
      $_lang = $_lang;
   endif;

   // Load all application models and controllers
   foreach (glob(__DIR__ . '/{models,controllers}/*.php', GLOB_BRACE) as $component):
      if ($component == (__DIR__ . '/models/Model.php') || $component == (__DIR__ . '/controllers/Controller.php'))
         continue;

      require_once $component;
   endforeach;
    
   $_database = new App\Core\Database;
   $_authenticator = new App\Core\Authenticator;
}

/**
 * Get configuration value
 * 
 * @param string $key
 * 
 * @return object
 */
function config($key)
{
   global $_config;

   return ($_config[$key] ?? null);
}

/**
 * Build view
 * 
 * @param string $view
 * @param array $params
 * 
 * @return Core\ViewManager
 */
function view($view, $params = [ ])
{
   return App\Core\ViewManager::make($view, $params);
}

/**
 * Get route by name or get current route
 *
 * @param string $name
 * 
 * @return Core\Route
 */
function route($name = null)
{
    global $routes;

    if (!$name)
        return $routes->route;
    
    return $routes->getRouteByName($name);
}

/**
 * Get database instance
 * 
 * @return App\Core\Database
 */
function database()
{
   global $_database;

   return $_database;
}

/**
 * Get authenticator instance
 * 
 * @return App\Core\Authenticator
 */
function auth()
{
   global $_authenticator;

   return $_authenticator;
}

/**
 * Limit string
 *
 * @param string $text
 * @param int $max_length
 * 
 * @return string
 */
function str_limit($text, $max_length)
{
   if (strlen($text) <= $max_length)
      return $text;

   return substr($text, 0, $max_length) . '...';    
}

/**
 * Get a function parameters
 *
 * @param object $function
 * @param mixed $class
 * 
 * @return array
 */
function get_function_parameters($function, $class = null)
{
   $result = [ ];
   $reflection = (!is_null($class) ? new ReflectionMethod($class, $function) : new ReflectionFunction($function));
   
   foreach ($reflection->getParameters() as $param)
      $result[] = $param->name;
   
   return $result;
}

/**
 * Parse function return data
 * 
 * @var mixed $data
 * 
 * @return string
 */
function api_data($data)
{
   parse_data($data);

   return (!is_array($data) ? $data : json_encode($data));
}

/**
 * Parse function return data
 *
 * @param mixed $data
 * 
 * @return string
 */
function parse_data(&$data)
{
   if (gettype($data) === 'array'):
      $data = array_map(function ($element) {
         if (gettype($element) === 'object' && !is_null(get_class($element)))
            return ((get_class($element) === 'stdClass') ? json_encode($element) : $element->__toArray());
         else
            return parse_data($element);
      }, $data);
   elseif (gettype($data) === 'object' && !is_null(get_class($data))):
      $data = ((get_class($data) === 'stdClass') ? json_encode($data) : $data->__toArray());
   endif;

   return $data;
}

/**
 * Get sent post parameters
 *
 * @param string $name
 * 
 * @return mixed
 */
function old($name)
{
   if (!isset($_POST)) return null;

   return ($_POST[$name] ?? null);
}

/**
 * Get value from language
 *
 * @param string $field
 * 
 * @return mixed|null
 */
function __($key) {
   global $_lang;

   if (!config('app.enable_multi_lang'))
      return $key;
   
   if (!array_key_exists($key, $_lang)):
      $key_array = explode('.', $key);

      $result_array = $_lang;
      foreach ($key_array as $lang_key):
         if (!array_key_exists($lang_key, $result_array))
            return $key;
         
         $result_array = $result_array[$lang_key];
      endforeach;

      $result_array = (($result_array == $_lang) ? $key : $result_array);
      return (is_array($result_array) ? json_encode($result_array) : $result_array);
   endif;
   
   $result_array = ($_lang[$key] ?? $key);
   return (is_array($result_array) ? json_encode($result_array) : $result_array);
}

/**
 * Create, remove or update cookie
 *
 * @param string $key
 * @param string $value
 * @param int $time
 * 
 * @return void
 */
function set_cookie($key, $value = '', $time = 0) {
   header('X-Frame-Options: ALLOW-FROM ' . config('app.url'));
   header("Set-Cookie:{$key}={$value}; path=/; max-age={$time}; secure; httpOnly");
}

/**
 * Set allowed methods for the current route
 *
 * @param string|array $methods
 * 
 * @return void
 */
function allowed_methods($methods) {
   if (!is_array($methods))
      $methods = [ $methods ];

   if (!in_array($_SERVER['REQUEST_METHOD'], $methods))
      die("Cannot {$_SERVER['REQUEST_METHOD']} {$_SERVER['REQUEST_URI']}");
}
