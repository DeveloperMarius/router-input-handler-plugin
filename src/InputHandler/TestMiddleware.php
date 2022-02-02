<?php

namespace SimpleRouter\Plugins\InputHandler;

use Pecee\Http\Request;

class TestMiddleware implements \Pecee\Http\Middleware\IMiddleware
{
    public function handle(Request $request) : void
    {
        /*
         * Example of InputValidation in a middleware:
         * inputHandler()->requireParameters(array(
         *    'userId' => function(InputItem $value){
         *        return $value->validate()->require()->isInteger()->valid();
         *    }
         * ));
         */
        echo 'Success Middleware' . PHP_EOL;
    }

}