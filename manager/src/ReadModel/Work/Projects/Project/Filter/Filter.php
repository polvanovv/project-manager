<?php


namespace App\ReadModel\Work\Projects\Project\Filter;

use App\Model\Work\Entity\Members\Member\Status;

class Filter
{
    public $name;
    public $status = Status::ACTIVE;
}