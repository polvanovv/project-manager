<?php

declare(strict_types = 1);

namespace App\ReadModel\User;


class DetailView
{
    public $id;
    public $date;
    public $email;
    public $role;
    public $status;
    public $first_name;
    public $last_name;

    /** @var NetworkView[] */
    public $networks;
}