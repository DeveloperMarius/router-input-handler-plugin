<?php

use Pecee\Http\Input\InputValidator;
use Pecee\Http\Input\InputValidatorItem;
use Pecee\SimpleRouter\SimpleRouter;
use SimpleRouter\Plugins\InputHandler\InputHandler;
use SimpleRouter\Plugins\InputHandler\TestMiddleware;

require '../vendor/autoload.php';
require '../src/InputHandler/IIInputItem.php';
require '../src/InputHandler/InputFile.php';
require '../src/InputHandler/InputItem.php';
require '../src/InputHandler/InputHandler.php';
include '../src/InputHandler/helper.php';

SimpleRouter::group(['middleware' => TestMiddleware::class], function () {
    SimpleRouter::post('/my/test/url', function(){
        echo 'Success Content' . PHP_EOL;
    });
});

global $_POST;
$_POST = [
    'username' => 'root',
    'password' => 'admin',
    'middleName' => null
];

$request = new \Pecee\Http\Request();
$request->setInputHandler(new InputHandler());
$request->setUrl((new \Pecee\Http\Url('/my/test/url'))->setHost('local.unitTest'));
$request->setMethod('post');
SimpleRouter::setRequest($request);

$inputHandler = SimpleRouter::request()->getInputHandler();

$request->validate(
    InputValidator::make()
        ->add(InputValidatorItem::make('username')->isString()->max(50))
        ->add(InputValidatorItem::make('password')->isString()->max(20))
);
echo 'Success Required with validation' . PHP_EOL;

var_dump(inputHandler()->post('username')->getValue());
var_dump(inputHandler()->value('username'));
var_dump(inputHandler()->all(['username'])['username']->getValue());

SimpleRouter::start();


