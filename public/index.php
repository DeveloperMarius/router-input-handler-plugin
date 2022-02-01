<?php

use Pecee\SimpleRouter\SimpleRouter;
use SimpleRouter\Plugins\InputHandler\InputHandler;
use SimpleRouter\Plugins\InputHandler\TestMiddleware;

require '../vendor/autoload.php';
require '../src/InputHandler/IIInputItem.php';
require '../src/InputHandler/InputFile.php';
require '../src/InputHandler/InputItem.php';
require '../src/InputHandler/InputHandler.php';
include '../src/InputHandler/helper.php';


SimpleRouter::group([], function () {
    SimpleRouter::post('/my/test/url', function(){
        echo 'Success Content' . PHP_EOL;
    }, ['middleware' => TestMiddleware::class]);
});

global $_POST;
$_POST = [
    'username' => 'root',
    'password' => 'admin'
];

$request = new \Pecee\Http\Request();
$request->setInputHandler(new InputHandler());
$request->setUrl((new \Pecee\Http\Url('/my/test/url'))->setHost('local.unitTest'));
$request->setMethod('post');
SimpleRouter::setRequest($request);

$inputHandler = SimpleRouter::request()->getInputHandler();

var_dump(inputHandler()->post('username')->getValue());
var_dump(inputHandler()->value('username'));
var_dump(inputHandler()->all(['username'])['username']->getValue());

SimpleRouter::start();


