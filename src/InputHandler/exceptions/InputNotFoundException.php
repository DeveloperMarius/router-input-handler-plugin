<?php

namespace SimpleRouter\Plugins\InputHandler\exceptions;

use Exception;
use Throwable;

class InputNotFoundException extends Exception
{

    /**
     * @var string $index
     */
    private $index;

    public function __construct($message, $index, $code = 0, Throwable $previous = null){
        $this->index = $index;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getIndex(): string{
        return $this->index;
    }

}