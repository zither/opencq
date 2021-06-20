<?php

namespace Xian\Object;

use Medoo\Medoo;
use Xian\Helper;

trait FromArrayTrait
{
    /**
     * @var Medoo
     */
    public $db;

    public static function fromArray(array $m): self
    {
        $mid = new static();
        $formattedColumns = [];
        foreach ($m  as $k => $v) {
            $formattedColumns[Helper::littleCamelCase($k)] = $v;
        }
        $vars = (new \ReflectionObject($mid))->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($vars as $v) {
            $key = $v->name;
            if (isset($formattedColumns[$key])) {
                $mid->$key = $formattedColumns[$key];
            }
        }
        return $mid;
    }

    public function withDatabase(Medoo $db)
    {
        $this->db = $db;
        return $this;
    }
}