<?php
namespace Xian\Task;

use SplMinHeap;

class Heap extends SplMinHeap
{
    protected function compare($value1, $value2)
    {
        if ($value1->timestamp < $value2->timestamp) {
            return 1;
        }
        if ($value1->timestamp == $value2->timestamp) {
            return 0;
        }
        return -1;
    }
}