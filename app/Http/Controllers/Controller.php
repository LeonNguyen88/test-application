<?php

namespace App\Http\Controllers;

use App\Supports\Traits\HasTransformer;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use HasTransformer;
}
