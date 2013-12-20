<?php
/*
Plugin Name: Wordpress Classes AutoLoader
Description: Provide Classes Auto-loading
Author: Xedinaska
Version: 1.0.0
*/

namespace WordPressClassesAutoLoader;

/**
 * Class Autoloader
 * Used to auto-require classes by ClassName / Namespace
 *
 * @package DesignerArt
 */
class Autoloader
{
    /**
     * If set in TRUE - if class not loaded then throw error_log() method
     */
    const DEBUG = false;

    /**
     * Use WP 'init' hook
     * Set 'OnLoad' action
     *
     * @access public
     */
    public function __construct()
    {
        add_action('init', array($this, 'init'));
    }

    /**
     * Set self autoload() method as autoloader
     *
     * @access public
     * @use spl_autoload_register()
     * @return void
     */
    public function init()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * AutoLoad function
     *
     * @access public
     * @param $className
     */
    public function autoload($className)
    {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';

        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }

        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        $path = dirname(dirname(__FILE__));

        $filePath = $path.DIRECTORY_SEPARATOR.$fileName;

        if(file_exists($filePath)) {
            require_once($filePath);
        } else {
            self::DEBUG ? error_log('Can\'t load class '.$className) : false;
        }
    }
}

//start plugin
$autoloader = new Autoloader();
