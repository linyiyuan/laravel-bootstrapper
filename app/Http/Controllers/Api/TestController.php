<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function test()
    {
         try {
             $url = 'https://video.fhkg4-2.fna.fbcdn.net/v/t42.9040-2/94816912_841955912992031_6104460081779179520_n.mp4?_nc_cat=105&_nc_sid=985c63&efg=eyJybHIiOjMwMCwicmxhIjo1MTIsInZlbmNvZGVfdGFnIjoibGVnYWN5X3NkIn0%3D&_nc_ohc=655t5wqbtJoAX_ErPtu&_nc_ht=video.fhkg4-2.fna&oh=ec0a5f1f5807cea3ad45d828c1d9442c&oe=5EA56D5A';

             file_put_contents('1.mp4', file_get_contents($url));
         }catch (\Exception $e) {
             return $this->errorExp($e);
         }
    }
}
