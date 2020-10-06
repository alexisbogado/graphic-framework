<?php

/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

namespace App\Core;

class ViewManager
{
    /**
     * Build the requested view
     * 
     * @param string $view
     * @param array $params
     * 
     * @return string
     */
    public static function make($view, $params = [ ])
    {
        self::build($view, $params, $viewData);
        $viewData = preg_replace("/@get[(]'(.*?)'[)]/", '', $viewData);
        $viewData = preg_replace('/{--(.*?)--}/ms', '', $viewData);

        foreach ($params as $param => $value)
            ${$param} = $value;

        preg_match_all('/{{ (.*?) }}/ms', $viewData, $matches);
        foreach ($matches[1] as $match)
            $viewData = str_replace("{{ {$match} }}", @eval("return {$match};"), $viewData);

        // TODO: if tests
        // Pages to get some ideas
        // https://stackoverflow.com/questions/22891468/if-and-foreach-in-a-templating-engine
        // Regex patterns
        // https://www.jotform.com/blog/php-regular-expressions/
        // @if\s*\((.*?)\)(.*)@endif
        
        return htmlspecialchars_decode($viewData);
    }

    /**
     * Build the requested view
     * 
     * @param string $view
     * @param array $params
     * @param string $viewData
     * 
     * @return void
     */
    private static function build($view, $params = [ ], &$viewData)
    {
        self::parsePath($view);
        self::loadContent($view, $params, $viewData);
        self::addIncludes($viewData, $params);
        self::getContent($viewData);
    }

    /**
     * Parse view path
     * 
     * @param string $view
     * 
     * @return string
     */
    private static function parsePath(&$view)
    {
        $view = str_replace('.', '/', $view);
        return $view;
    }

    /**
     * Get view content from file
     * 
     * @param string $view
     * @param array $params
     * @param string $viewData
     * 
     * @return string
     */
    private static function loadContent($view, $params = [ ], &$viewData)
    {
        $file_path = __DIR__ . "/../views/{$view}.php";
        if (!file_exists($file_path)):
            $viewData = "Cannot load view '{$view}'";
        else:
            extract($params);

            ob_start();

            include($file_path);
            $viewData = ob_get_clean();
        endif;

        return $viewData;
    }

    /**
     * Render view into the current view
     * 
     * @param string $viewData
     * @param array $params
     * 
     * @return string
     */
    private static function addIncludes(&$viewData, $params)
    {
        preg_match_all("/@add[(]'(.*?)'[)]/", $viewData, $matches);
        foreach ($matches[1] as $match):
            $view_name = $match;
            self::build($view_name, $params, $content);

            $viewData = str_replace("@add('{$match}')", $content, $viewData);
        endforeach;

        return $viewData;
    }

    /**
     * Print view sections
     * 
     * @param string $viewData
     * 
     * @return string
     */
    private static function getContent(&$viewData)
    {
        preg_match_all("/@content[(]'(.*?)'[)](.*?)@endcontent/s", $viewData, $contents);
        for ($i = 0; $i < count($contents[0]); $i++):
            $viewData = preg_replace("/@get[(]'{$contents[1][$i]}'[)]/", $contents[2][$i], $viewData);
            $viewData = preg_replace("/@content[(]'{$contents[1][$i]}'[)](.*?)@endcontent/s", '', $viewData);
        endfor;
        
        return $viewData;
    }
}
