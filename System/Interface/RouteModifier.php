<?php

namespace Jauhar\System\Interface;

use Jauhar\System\Routes\Route;

interface RouteModifier
{
    public function modifyRoute(Route $route): Route;
}
