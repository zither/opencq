<?php
namespace Xian;


interface EncoderInterface
{
    public function encode(string $str): string;

    public function decode(string $str): string;
}