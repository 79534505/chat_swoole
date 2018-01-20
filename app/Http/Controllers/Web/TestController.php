<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Providers\Helper\Helper;

class TestController extends Controller
{
    public function test()
    {
        dd(Helper::authcode('test', 'ENCODE', 'test_'));
    }
}