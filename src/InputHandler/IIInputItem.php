<?php

namespace SimpleRouter\Plugins\InputHandler;

use Pecee\Http\Input\IInputItem;

interface IIInputItem extends IInputItem{

    public function getIndex(): string;

    public function setIndex(string $index): IInputItem;

    public function getName(): ?string;

    public function setName(string $name): IInputItem;

    public function getValue();

    public function setValue($value): IInputItem;

    public function hasInputItems(): bool;

    public function getInputItems();

    public function __toString(): string;

}