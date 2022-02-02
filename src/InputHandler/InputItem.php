<?php

namespace SimpleRouter\Plugins\InputHandler;

use ArrayIterator;
use IteratorAggregate;
use Pecee\Http\Input\IInputItem;

class InputItem implements IIInputItem, IteratorAggregate
{
    public $index;
    public $name;
    public $value;

    private $validator = null;
    private $parser = null;

    /**
     * InputItem constructor.
     * @param string $index
     * @param mixed $value
     */
    public function __construct(string $index, $value = null)
    {
        $this->index = $index;
        $this->value = $value;

        // Make the name human friendly, by replace _ with space
        $this->name = ucfirst(trim(str_replace('_', ' ', strtolower($this->index))));
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @param string $index
     * @return InputItem
     */
    public function setIndex(string $index): IInputItem
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set input name
     * @param string $name
     * @return InputItem
     */
    public function setName(string $name): IInputItem
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        if(is_array($this->value)){
            return $this->parseValueFromArray($this->value);
        }
        return $this->value;
    }

    /**
     * @return bool
     */
    public function hasInputItems(): bool
    {
        return is_array($this->value);
    }

    /**
     * @return InputItem[]
     */
    public function getInputItems()
    {
        if(is_array($this->value)){
            return $this->value;
        }
        return array();
    }

    /**
     * @param array $array
     * @return array
     */
    protected function parseValueFromArray(array $array): array
    {
        $output = [];
        /* @var $item InputItem */
        foreach ($array as $key => $item) {

            if ($item instanceof IInputItem) {
                $item = $item->getValue();
            }

            $output[$key] = is_array($item) ? $this->parseValueFromArray($item) : $item;
        }

        return $output;
    }

    /**
     * Set input value
     * @param mixed $value
     * @return static
     */
    public function setValue($value): IInputItem
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param bool $forceNew
     * @return InputValidator
     */
    public function validate(bool $forceNew = false): InputValidator
    {
        if($this->validator === null || $forceNew)
            $this->validator = new InputValidator($this);
        return $this->validator;
    }

    /**
     * @param bool $forceNew
     * @return InputParser
     */
    public function parse(bool $forceNew = false): InputParser
    {
        if($this->parser === null || $forceNew)
            $this->parser = new InputParser($this);
        return $this->parser;
    }

    public function __toString(): string
    {
        return json_encode($this->getValue());
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->getInputItems());
    }
}