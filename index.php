<?php

declare(strict_types=1);

require('src/Core/core.php');

use App\Core\{Config, CookieLogin};

$config = new Config();
$cookieLogin = new CookieLogin($config);
$cookieLogin->setCookieLogin();

$appSettings = require($_SERVER['DOCUMENT_ROOT'] . '/src/Config/settings.php');
$settings = $appSettings[$_GET['action']];

switch ($settings['role']) {
    case 'user':
        if (!$_SESSION['user']) {
            header('Location: ' . $config->getUrl() . '/logowanie');
            exit;
        }
        break;
    case 'admin':
        if (!$_SESSION['admin']) {
            header('Location: ' . $config->getUrl() . '/logowanie');
            exit;
        }
        break;
    default:
        break;
}

switch ($settings['option']) {
    case 'page':
        $class = 'App\\Controller\\' . $settings['name'] . 'Controller';
        $method = $settings['name'] . 'Action';

        $controller = new $class();
        $array = $controller->$method($_REQUEST, $_SESSION);
        break;
    case 'ajax':
        $class = 'App\\Controller\\Ajax\\' . $settings['name'] . 'Controller';
        $method = $settings['name'] . 'Action';

        $controller = new $class();
        $array = $controller->$method($_REQUEST);

        if ($array['redirection']) break;
        include($array['content']);
        exit;
    case 'api':
        $class = 'App\\Controller\\Api\\' . $settings['name'] . 'Controller';
        $method = $settings['name'] . 'Action';

        $controller = new $class();
        $array = $controller->$method(
            $_SERVER,
            json_decode(file_get_contents('php://input'), true)
        );

        if ($array['redirection']) break;
        echo json_encode($array);
        exit;
    default:
        $array = array();

        $array['layout'] = 'src/Layout/main/main.php';
        $array['content'] = 'src/View/default/default.php';
        $array['activeMenu'] = '';
        $array['title'] = 'Pusta strona';
        break;
}

if ($array['redirection']) {
    header('Location: ' . $config->getUrl() . $array['path']);
    exit;
}

foreach ($array as $key => $value) {
    if (
        $key !== 'error'
        && $key !== 'message'
        && $key !== 'pageNavigator'
        && is_string($value)
    ) {
        $array[$key] = htmlspecialchars($value);
    } elseif (is_array($value)) {
        foreach ($value as $key2 => $value2) {
            if (is_string($value2)) {
                $array[$key][$key2] = htmlspecialchars($value2);
            } elseif (is_array($value2)) {
                foreach ($value2 as $key3 => $value3) {
                    if (is_string($value3)) {
                        $array[$key][$key2][$key3] = htmlspecialchars($value3);
                    }
                }
            }
        }
    }
}

$array['url'] = $config->getUrl();

include($array['layout'] ?? 'src/Layout/main/main.php');
