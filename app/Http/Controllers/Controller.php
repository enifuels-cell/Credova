<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
abstract class Controller
{
    //
=======
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
}
