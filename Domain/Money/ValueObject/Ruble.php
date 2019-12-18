<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 18:29
 */

namespace app\Domain\Money\ValueObject;


use app\Domain\Money\Exception\NegativeAmountException;
use app\Domain\Money\Specification\Money;

class Ruble extends Money
{
    protected $amount;

    /**
     * Ruble constructor.
     * @param int $amount
     * @throws NegativeAmountException
     */
    public function __construct(int $amount)
    {
        if ($amount < 0) {
            throw new NegativeAmountException();
        }

        $this->amount = $amount;
    }
}