<?php

/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

namespace App\Core;

use \stdClass;

class Route
{
    /**
     * Route URI
     *
     * @var string
     */
    public $path;

    /**
     * Route method
     *
     * @var string
     */
    public $method;

    /**
     * Invokable object to show when route is called
     *
     * @var object
     */
    public $invokable;

    /**
     * Route name
     *
     * @var string
     */
    public $name;

    /**
     * Route arguments
     * 
     * @var array
     */
    public $arguments;
    
    /**
     * Is an API route?
     * 
     * @var bool
     */
    public $isApi;

    /**
     * Class constructor
     *
     * @param string $path
     * @param string $method
     * @param object $invokable
     * @param bool $isApi
     */
    public function __construct($path, $method, $invokable, $isApi = false)
    {
        $this->path = $path;
        $this->method = $method;
        $this->invokable = $invokable;
        $this->arguments = [ ];
        $this->isApi = $isApi;
    }

    /**
     * Add name to route
     *
     * @param string $name
     * 
     * @return void
     */
    public function name($name)
    {
        $this->name = $name;
    }

    /**
     * Get route path
     *
     * @param mixed ...$parameters
     * 
     * @return string
     */
    public function path(...$parameters)
    {
        $path = $this->path;
        preg_match_all('/{(.*?)}/', $path, $matches);
        if (count($matches) > 0)
            foreach ($matches[0] as $key => $value):
                $path = str_replace($value, ($parameters[$key] ?? $value), $path);
            endforeach;

        return config('app.url') . $path;
    }

    /**
     * Render route
     * 
     * @param string $parsed_uri
     *
     * @return object
     */
    public function render($parsed_uri)
    {
        $data = null;

        if ($this->isApi)
            header('Content-type: application/json');

        if (gettype($this->invokable) === 'object'):
            $arguments = $this->getArguments($parsed_uri, $this->invokable);
            $data = call_user_func_array($this->invokable, $arguments);
        else:
            $function_array = explode('@', $this->invokable);
            $controller_name = "App\\Controllers\\{$function_array[0]}";
            $controller = new $controller_name;
            $arguments = $this->getArguments($parsed_uri, $function_array[1], $controller);
            $data = call_user_func_array([ $controller, $function_array[1] ], $arguments);
        endif;

        return ($this->isApi ? api_data($data) : $data) ?? '';
    }

    /**
     * Get HTTP parameters
     * 
     * @return \stdClass
     */
    public function requestParameters()
    {
        $request = new stdClass;

        switch (strtoupper($this->method)):
            case 'POST':
                $parameters = json_decode(file_get_contents('php://input'), true);
                if (is_array($parameters)) $_POST = array_merge($parameters, $_POST);

                $request->input = json_decode(json_encode($_POST));
                $request->files = json_decode(json_encode($_FILES));
                $request->query = json_decode(json_encode($_GET));
            break;

            case 'GET':
                $request->input = json_decode(json_encode($_GET));
                $request->files = $_FILES;
            break;
            
            case 'DELETE':
            case 'PUT':
                parse_str(file_get_contents('php://input'), $parameters);
                if (is_array($parameters)) $_POST = array_merge($parameters, $_POST);

                $request->input = json_decode(json_encode($_POST));
                $request->files = json_decode(json_encode($_FILES));
                $request->query = json_decode(json_encode($_GET));
            break;
        endswitch;

        return $request;
    }

    /**
     * Get uri arguments and pass to invokable
     * 
     * @param string $parsed_uri
     * @param object $invokable
     * @param object $class
     *
     * @return array
     */
    public function getArguments($parsed_uri, $invokable, $class = null)
    {
        $parameters = get_function_parameters($invokable, $class);
        $arguments = [ ];
        $uri_array = explode('/', $this->path);
        $current_uri = explode('/', $parsed_uri);

        for ($i = 0; $i < count($uri_array); $i++):
            $uri_param = $uri_array[$i];
            if (!strstr($uri_param, '{'))
                continue;

            $arguments[] = ($current_uri[$i] ?? null);
        endfor;

        if (count($parameters) > count($arguments))
            $arguments[] = $this->requestParameters();

        return $arguments;
    }
}
