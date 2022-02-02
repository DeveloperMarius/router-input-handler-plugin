<?php

namespace SimpleRouter\Plugins\InputHandler;

class InputParser{

    /**
     * @var InputItem $inputItem
     */
    private $inputItem;
    /**
     * @var mixed $value
     */
    private $value;

    /**
     * @param InputItem $inputItem
     */
    public function __construct(InputItem $inputItem){
        $this->inputItem = $inputItem;
        $this->value = $inputItem->getValue();
    }

    /**
     * @return mixed
     */
    public function getValue(){
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    private function setValue($value): self{
        $this->value = $value;
        return $this;
    }

    /**
     * @return InputItem
     */
    public function getInputItem(): InputItem{
        return $this->inputItem;
    }

    /**
     * @param array $allowed_tags
     * @return $this
     */
    public function stripTags(array $allowed_tags = array()): self{
        return $this->setValue(strip_tags($this->getValue(), $allowed_tags));
    }

    /**
     * @return $this
     */
    public function stripTags2(): self{
        return $this->setValue(filter_var($this->getValue(), FILTER_SANITIZE_STRING));
    }

    /**
     * @return $this
     */
    public function htmlSpecialChars(): self{
        return $this->setValue(htmlspecialchars($this->getValue(), ENT_QUOTES | ENT_HTML5));
    }

    /**
     * Best practise with Inputs from Users
     *
     * @return $this
     */
    public function sanitize(): self{
        return $this->htmlSpecialChars();
    }

    /**
     * @return $this
     */
    public function sanitizeEmailAddress(): self{
        return $this->setValue(filter_var($this->getValue(), FILTER_SANITIZE_EMAIL));
    }

    /**
     * @return bool|null
     */
    public function toBoolean(): ?bool{
        if(filter_var($this->getValue(), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null){
            $this->setValue(boolval($this->getValue()));
            return $this->getValue();
        }
        return null;
    }

    /**
     * @return string
     */
    public function toString(): string{
        $this->setValue(strval($this->getValue()));
        return $this->getValue();
    }

    /**
     * @return int|null
     */
    public function toInteger(): ?int{
        if(is_string($this->getValue()) && is_numeric($this->getValue()) && strpos($this->getValue(), '.') === false){
            $this->setValue(intval($this->getValue()));
            return $this->getValue();
        }
        return null;
    }

    /**
     * @return string
     */
    public function urlEncode(): string{
        return urlencode($this->getValue());
    }

    /**
     * @return string
     */
    public function base64Encode(): string{
        return base64_encode($this->getValue());
    }

    /**
     * Credits: https://stackoverflow.com/questions/2791998/convert-string-with-dashes-to-camelcase#answer-2792045
     * @param string $separator
     * @param bool $capitalizeFirstCharacter
     * @return array|string|string[]
     */
    function toCamelCase(string $separator = '_', bool $capitalizeFirstCharacter = false)
    {
        $str = str_replace('-', '', ucwords($this->getValue(), $separator));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    /**
     * @param string $separator
     * @return array|string|string[]
     */
    function fromCamelCase(string $separator = '_')
    {
        $value = lcfirst($this->getValue());

        return preg_replace_callback('/[A-Z]/', function($value){
            return '_' . strtolower($value[0]);
        }, $value);
    }
}