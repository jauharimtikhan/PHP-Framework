<?php

namespace Jauhar\System\Routes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Get extends Route
{
    protected string $path;

    public function __construct(string $path = "")
    {
        parent::__construct("GET", $path);
    }
}
