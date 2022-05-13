<?php

function autoload($className)
{
    if (!defined('AUTHOR_NAME')) {
        define('AUTHOR_NAME', '.');
    }
    if (!defined('PROJECT_NAME')) {
        define('PROJECT_NAME', '.');
    }
    if (!defined('PROJECT_DIR')) {
        define('PROJECT_DIR', 'src');
    }
    $className = preg_replace('#^' . AUTHOR_NAME . '\\\\' . PROJECT_NAME . '#', '', $className);
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);

    $arr = array_filter(explode(DIRECTORY_SEPARATOR, $className));
    $module = array_shift($arr);
    $className = implode(DIRECTORY_SEPARATOR, $arr);

    if (empty($className)) {
        $className = $module;
        $module = '';
    }

    if (is_file(__DIR__ . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $className . '.php')) {
        require_once(__DIR__ . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $className . '.php');
    }

    if (is_file(__DIR__ . DIRECTORY_SEPARATOR . 'external' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $className . '.php')) {
        require_once(__DIR__ . DIRECTORY_SEPARATOR . 'external' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $className . '.php');
    }

    if (is_file(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . PROJECT_DIR . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . PROJECT_DIR . DIRECTORY_SEPARATOR . $className . '.php')) {
        require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . PROJECT_DIR . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . PROJECT_DIR . DIRECTORY_SEPARATOR . $className . '.php');
    }

    if (is_file(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . PROJECT_DIR . DIRECTORY_SEPARATOR . $className . '.php')) {
        require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . PROJECT_DIR . DIRECTORY_SEPARATOR . $className . '.php');
    }

    if (is_file(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . PROJECT_DIR . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $className . '.php')) {
        require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . PROJECT_DIR . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $className . '.php');
    }
}

spl_autoload_register('autoload');
