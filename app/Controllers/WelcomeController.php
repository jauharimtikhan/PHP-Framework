<?php

namespace App\Controllers;

use Jauhar\System\BaseController;
use Jauhar\System\Controller;
use Jauhar\System\Routes\Get;


#[Controller('/')]
class WelcomeController extends BaseController
{

    #[Get]
    public function index()
    {
        return $this->view('Welcome');
    }
}
