<?php

use Pecee\SimpleRouter\SimpleRouter;
use SimpleRouter\Plugins\InputHandler\exceptions\InputNotFoundException;
use SimpleRouter\Plugins\InputHandler\exceptions\InputValidationException;
use SimpleRouter\Plugins\InputHandler\InputHandler;
use SimpleRouter\Plugins\InputHandler\InputItem;
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
\SimpleRouter\Plugins\InputHandler\InputValidator::setTrowErrors(true);//default mode

inputHandler()->requireParameters(array(
    'username' => function(InputItem $value){
        return $value->validate()->require()->minLength(2)->maxLength(20)->isString()->valid();
    },
    'middleName' => function(InputItem $value){
        return $value->validate()->canBeNull()->minLength(2)->maxLength(20)->isString()->valid();
    }
));
echo 'Success Required with validation' . PHP_EOL;

try{
    inputHandler()->requireParameters(array(
        'username' => function(InputItem $value){
            return $value->validate()->require()->minLength(6)->maxLength(20)->isString()->valid();
        }
    ));
}catch(InputValidationException $e){
    echo 'Success Input Validation Error' . PHP_EOL;
}

try{
    inputHandler()->requireParameters(array(
        'middleName' => function(InputItem $value){
            return $value->validate()->require()->minLength(2)->maxLength(20)->isString()->valid();
        }
    ));
}catch(InputValidationException $e){
    echo 'Success Input Validation Error 2' . PHP_EOL;
}

try{
    inputHandler()->requireParameters(array(
        'permission'
    ));
}catch(InputNotFoundException $e){
    echo 'Success Input Not Found' . PHP_EOL;
}

var_dump(inputHandler()->post('username')->getValue());
var_dump(inputHandler()->value('username'));
var_dump(inputHandler()->all(['username'])['username']->getValue());

SimpleRouter::start();


