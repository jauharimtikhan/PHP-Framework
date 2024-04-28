<?php

function asset(string $path)
{
    if ($path) {
        $httpHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $urlParts = parse_url($httpHost);
        $httpProtocol = isset($urlParts['scheme']) ? $urlParts['scheme'] : 'http';
        $host = $httpProtocol . "://" . $_SERVER['HTTP_HOST'] . "/assets/" . $path;
        return $host;
    } else {
        return null;
    }
}
