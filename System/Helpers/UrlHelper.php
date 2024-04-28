<?php
function route(string $route = null)
{
    $httpHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $urlParts = parse_url($httpHost);
    $httpProtocol = isset($urlParts['scheme']) ? $urlParts['scheme'] : 'http';
    $host = $httpProtocol . "://" . $_SERVER['HTTP_HOST'] . "/" . $route;

    return $host;
}

function redirect($url)
{
    header("Location: $url");
    exit();
}
