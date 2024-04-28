<?php

namespace Jauhar\System;

class BaseController
{

    protected $input;
    public function __construct()
    {
        $this->input = new Input();
    }

    public function view($views, $data = array())
    {

        require dirname(__DIR__) . "../app/Views/$views.php";
    }

    public function template($template, $view, $data = array())
    {
        require dirname(__DIR__) . "../App/Views/$template/$view.php";
    }

    public function model($model)
    {
        require dirname(__DIR__) . "../app/Models/$model.php";
        return new $model;
    }
}
