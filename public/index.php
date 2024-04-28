<?php
require "../vendor/autoload.php";
require "../System/Helpers/AssetHelper.php";
require "../System/Helpers/UrlHelper.php";

use Dotenv\Dotenv as Dotenvs;
use Jauhar\System\App;
use App\CoreModule;

$dotenv = Dotenvs::createImmutable(__DIR__ . "/../");
$dotenv->load();


define('ENVIRONMENT', isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : getenv('APP_ENV'));

switch (ENVIRONMENT) {
    case 'dev':

        error_reporting(-1);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        break;

    case 'test':
    case 'prod':
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '7.2', '>=')) {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;

    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // EXIT_ERROR
}

$app = new App();
$app->registerModule(new CoreModule());
$app->run();
