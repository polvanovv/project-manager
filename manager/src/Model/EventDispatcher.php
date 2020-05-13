<?php

declare(strict_types = 1);

namespace App\Model;


interface EventDispatcher
{
    public function dispatch(array $event): void;
}