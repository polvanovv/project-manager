<?php

declare(strict_types = 1);

namespace App\Model\User\Entity\User;


use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class User
 *
 * @package App\Model\User\Entity\User
 * @author Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 *
 */
class User
{
    private const STATUS_NEW = 'new';
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    /**
     * @var Id
     */
    private $id;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var string
     */
    private $passwordHash;
    /**
     * @var string
     */
    private $confirmToken;

    /**
     * @var ResetToken|null
     */
    private $resetToken;

    /**
     * @var string
     */
    private $status;


    /**
     * @var ArrayCollection
     */
    private $networks;


    /**
     * User constructor.
     * @param Id $id
     * @param \DateTimeImmutable $date
     */
    public function __construct(Id $id, \DateTimeImmutable $date)
    {
        $this->id = $id;
        $this->createdAt = $date;
        $this->status = self::STATUS_NEW;
        $this->networks = new ArrayCollection();
    }

    /**
     * @param Email $email
     * @param string $hash
     * @param string $token
     */
    public  function signUpByEmail(Email $email, string $hash, string $token): void
    {

        $this->email = $email;
        $this->passwordHash = $hash;
        $this->confirmToken = $token;
        $this->status = self::STATUS_WAIT;
    }

    /**
     * @param string $network
     * @param string $identity
     */
    public  function signUpByNetwork(string $network, string $identity): void
    {
        if (!$this->isNew()) {
            throw new \DomainException('User is already sign up.');
        }

        $this->attachedNetwork($network, $identity);
        $this->status = self::STATUS_ACTIVE;

    }

    /**
     * @param string $network
     * @param string $identity
     */
    private function attachedNetwork(string $network, string $identity): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->isForNetwork($network)) {
                throw new \DomainException('Network is already attached.');
            }
        }

        $this->networks->add(new Network($this, $network, $identity));
        
    }

    /**
     * @param ResetToken $token
     * @param \DateTimeImmutable $date
     */
    public function requestPasswordReset(ResetToken $token, \DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('User is not active.');
        }
        if (!$this->email) {
            throw new \DomainException('Email is not specified.');
        }
        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Resetting is already requested.');
        }

        $this->resetToken = $token;
    }

    /**
     * @param \DateTimeImmutable $date
     * @param string $hash
     */
    public function passwordReset(\DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new \DomainException('Resetting is not requested.');
        }
        if ($this->resetToken->isExpiredto($date)) {
            throw new \DomainException('Reset token is expired.');
        }

        $this->passwordHash = $hash;
        
    }

    public function confirmSignUp(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already confirmed');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }


    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }
    /**
     * @return bool
     */
    public function isWait():bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    /**
     * @return bool
     */
    public function isActive():bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    /**
     * @return Network[]
     */
    public function getNetworks(): array
    {
        return $this->networks->toArray();
    }

    /**
     * @return ResetToken|null
     */
    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

}