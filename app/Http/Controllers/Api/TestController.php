<?php

namespace App\Http\Controllers\Api;


class TestController extends CommonController
{
    public function test()
    {
        $payTypeConf = config('inc.payTypeConf');

        dd($payTypeConf);

    }
}
