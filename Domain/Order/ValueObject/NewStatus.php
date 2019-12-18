<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 18:08
 */

namespace app\Domain\Order\ValueObject;


use app\Domain\Order\Specification\Status;

class NewStatus extends Status
{
    protected $name = 'new';
}