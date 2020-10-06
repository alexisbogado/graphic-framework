<?php

/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

namespace App\Core;

use App\Controllers\IndexController;

class RoutesManager
{
    /**
     * Application routes variable
     *
     * @var App\Core\Route[]
     */
    private $routesList;

    /**
     * Current route
     *
     * @var App\Core\Route
     */
    public $route;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->routesList = [ ];
    }

    /**
     * Magic function to add route to the specific method
     *
     * @param string $function
     * @param array $arguments
     * 
     * @return mixed
     */
    public function __call($function, $arguments)
    {
        switch ($function):
            case 'get':
            case 'post':
            case 'delete':
            case 'put':
                // Add method to passed arguments
                array_splice($arguments, 1, 0, strtoupper($function));
                return $this->add(...$arguments);
            break;

            case 'api':
                // Change method and uri positions
                $uri = array_splice($arguments, 1, 1);
                array_splice($arguments, 0, 0, $uri);

                // Set argument $isApi to true
                array_splice($arguments, 3, 0, true);
                return $this->add(...$arguments);
            break;
        endswitch;
    }

    /**
     * Add route to list
     *
     * @param string $uri
     * @param string $method
     * @param object $invokable
     * @param object $isApi
     * 
     * @return App\Core\Route
     */
    public function add($uri, $method, $invokable, $isApi = false)
    {
        if (!strstr($uri, '/'))
            $uri = "/{$uri}";

        $uri = ($isApi ? "/api{$uri}" : $uri);
        $route = new Route($uri, strtolower($method), $invokable, $isApi);
        $uri_array = explode('/', $uri);
        for ($i = 0; $i < count($uri_array); $i++):
            $uri_param = $uri_array[$i];
            if (!strstr($uri_param, '{'))
                continue;

            $route->arguments[$uri_param] = "{$i}";
        endfor;
        
        $this->routesList[] = $route;
        return $route;
    }

    /**
     * Render the requested route
     *
     * @param string $method
     * @param string $uri
     * 
     * @return App\Core\View
     */
    public function render($method, $uri)
    {
        $this->parseUri($uri);
        
        $route = array_values(array_filter($this->routesList, function ($route) use ($method, $uri) {
            return $route->method == strtolower($method) && $route->path == $uri;
        }))[0] ?? null;
        
        if (!$route)
            foreach ($this->routesList as $current_route):
                if ($current_route->method != strtolower($method))
                    continue;

                $uri_array = explode('/', $uri);
                $current_main_uri = (($uri_array[1] == 'api') ? $uri_array[2] : $uri_array[1]);
                $route_array = explode('/', $current_route->path);
                $route_main_uri = (($route_array[1] == 'api') ? $route_array[2] : $route_array[1]);
                $optional_parameters = array_filter(array_keys($current_route->arguments), function ($argument) {
                    return (strpos($argument, '?', strlen($argument) - 2) !== false);
                });
                
                if (!strstr($route_main_uri, '{') && $current_main_uri != $route_main_uri)
                    continue;
                elseif (strstr($route_main_uri, '{') && (empty($current_main_uri) && count($optional_parameters) < 1))
                    continue;
                elseif(count($uri_array) != count($route_array) && (count($uri_array) != (count($route_array) - count($optional_parameters))))
                    continue;

                foreach ($route_array as $key => $route_parameter):
                    $is_parameter = preg_match('/{(.*?)}/', $route_parameter);
                    $existing_parameter = ($is_parameter && in_array($key, array_keys($uri_array)));

                    if ($existing_parameter)
                        $route_array[$key] = $uri_array[$key];
                    elseif ($is_parameter)
                        unset($route_array[$key]);
                endforeach;

                if (count(array_diff($uri_array, $route_array)) > 0)
                    continue;
                
                $route = $current_route;
                break;
            endforeach;

        if (!$route)
            goto Error;
        
        $this->route = $route;
        return $route->render($uri);

        Error:
        return view('error');
    }

    /**
     * Get route by name
     *
     * @param string $name
     * 
     * @return App\Core\Route
     */
    public function getRouteByName($name)
    {
        return array_values(array_filter($this->routesList, function ($route) use ($name) {
            return $route->name == $name;
        }))[0] ?? null;
    }

    /**
     * Remove parameters from URI
     *
     * @param string $uri
     * 
     * @return string
     */
    public function parseUri(&$uri)
    {
        if (strpos($uri, '?') !== false):
            $uri_array = explode('?', $uri);
            $uri = $uri_array[0];
        endif;

        return $uri;
    }
}
