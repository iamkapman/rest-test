<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 19:08
 */

namespace app\Domain\Money\Specification;


abstract class Money
{
    protected $amount;

    public function getAmount()
    {
        return $this->amount;
    }
}