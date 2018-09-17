<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(indexes={
 *     @ORM\Index(name="disabled_updated", columns={"disabled", "updated"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email = null;

    /**
     * @ORM\Column(type="integer")
     */
    private $balance = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disabled = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="user", orphanRemoval=true)
     */
    private $transactions;

    public function __construct() {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(?string $email): self {
        $this->email = $email;

        return $this;
    }

    public function getBalance() {
        return $this->balance;
    }

    public function setBalance($balance): self {
        $this->balance = $balance;

        return $this;
    }

    public function addBalance($amount): self {
        $this->balance += $amount;

        return $this;
    }

    function isDisabled() : bool {
        return $this->disabled;
    }

    function setDisabled(bool $disabled) : self {
        $this->disabled = $disabled;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection {
        return $this->transactions;
    }

    /**
     * @ORM\PrePersist()
     * @param LifecycleEventArgs $event
     */
    public function setHistoryColumnsOnPrePersist(LifecycleEventArgs $event) {
        if (!$this->getCreated()) {
            $this->setCreated(new \DateTime());
        }
    }

    /**
     * @ORM\PreUpdate()
     * @param PreUpdateEventArgs $event
     */
    public function setHistoryColumnsOnPreUpdate(PreUpdateEventArgs $event) {
        if (!$event->hasChangedField('updated')) {
            $this->setUpdated(new \DateTime());
        }
    }
}
