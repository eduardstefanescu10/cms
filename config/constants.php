<?php


// Constants
define('DS',          DIRECTORY_SEPARATOR);
define('ROOT',        dirname(__DIR__) . DS);
define('APP',         ROOT . 'app' . DS);
define('CONTROLLERS', APP . 'controllers' . DS);
define('VIEWS',       APP . 'views' . DS);
define('STORAGE',     ROOT . 'storage' . DS);
define('LOGS',        STORAGE . 'logs' . DS);
define('VENDOR',      ROOT . 'vendor' . DS);
define('CMS_SRC',     VENDOR . 'cms' . DS . 'cms' . DS . 'src' . DS);


?>