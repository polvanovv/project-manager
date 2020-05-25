<?php

declare(strict_types = 1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class Name
 *
 * @package App\Model\User\Entity\User
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 * @ORM\Embeddable()
 */
class Name
{

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $first;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $last;

    /**
     * Name constructor.
     * @param string $first
     * @param string $last
     */
    public function __construct(string $first, string $last)
    {
        Assert::notEmpty($first);
        Assert::notEmpty($last);

        $this->first = $first;
        $this->last = $last;
    }

    /**
     * @return string
     */
    public function getFirst(): string
    {
        return $this->first;
    }

    /**
     * @return string
     */
    public function getLast(): string
    {
        return $this->last;
    }


    /**
     * @return string
     */
    public function getFull()
    {
        return $this->first . '' . $this->last;
    }

}