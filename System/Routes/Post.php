<?php

namespace Jauhar\System\Routes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Post extends Route
{
    protected string $path;

    public function __construct(string $path = "")
    {
        parent::__construct("Post", $path);
    }
}
