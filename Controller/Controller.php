<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 15.12.2019
 * Time: 12:49
 */

namespace app\Controller;


abstract class Controller
{
    /**
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     */
    public function callMethod(string $method, array $args = [])
    {
        $reflection = new \ReflectionMethod($this, $method);

        $pass = [];
        foreach ($reflection->getParameters() as $parameter) {
            if (isset($args[$parameter->getName()])) {
                $pass[] = $args[$parameter->getName()];
            } else {
                $pass[] = $parameter->getDefaultValue();
            }
        }

        return $reflection->invokeArgs($this, $pass);
    }
}