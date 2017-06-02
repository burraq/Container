<?php

namespace Burraq\Container;

interface ContainerInterface extends \ArrayAccess
{
    public function raw($key);

    public function keys();
}
