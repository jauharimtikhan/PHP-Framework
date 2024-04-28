<?php

namespace Jauhar\System;

abstract class Module
{
    /**
     * @return string[]
     */
    abstract public function getControllers(): array;
}
