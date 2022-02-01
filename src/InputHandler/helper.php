<?php

use SimpleRouter\Plugins\InputHandler\InputHandler;

/**
 * @return InputHandler
 */
function inputHandler(){
    $inputHandler = \Pecee\SimpleRouter\SimpleRouter::request()->getInputHandler();
    if($inputHandler instanceof InputHandler)
        return $inputHandler;
    else return null;
}