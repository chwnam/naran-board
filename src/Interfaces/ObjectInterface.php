<?php


namespace Naran\Board\Interfaces;


interface ObjectInterface
{
    public static function fromArray($array);

    public function toArray();

    public static function getDefault();
}
