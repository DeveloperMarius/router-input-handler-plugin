<?php

namespace SimpleRouter\Plugins\InputHandler;

use Pecee\Http\Request;

class TestMiddleware implements \Pecee\Http\Middleware\IMiddleware
{
    public function handle(Request $request) : void
    {
        echo 'Success Middleware' . PHP_EOL;
    }

}