<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 15.12.2019
 * Time: 18:55
 */

namespace app\Specification;


class BaseEntity
{
    public function toArray()
    {
        return get_object_vars($this);
    }
}