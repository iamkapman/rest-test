<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 18:11
 */

namespace app\Domain\Order\Specification;


abstract class Status
{
    protected $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}