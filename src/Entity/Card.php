<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 */
class Card implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *     max="255",
     *     maxMessage="Name must contain maximum 255 characters.",
     *     groups={"set_name"}
     * )
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max="4",
     *     min="4",
     *     maxMessage="Name must contain maximum 4 characters.",
     *     minMessage="Name must contain minimum 4 characters.",
     * )
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $last4;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @Assert\NotBlank()
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $cardId;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max="16",
     *     min="16",
     *     maxMessage="Number must contain maximum 16 characters.",
     *     minMessage="Number must contain minimum 16 characters.",
     * )
     * @var string
     */
    private $number;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max="2",
     *     min="1",
     *     maxMessage="Month must contain maximum 2 characters.",
     *     minMessage="Month must contain minimum 1 characters.",
     * )
     * @var integer
     */
    private $expMonth;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max="4",
     *     min="4",
     *     maxMessage="Year must contain maximum 4 characters.",
     *     minMessage="Year must contain minimum 4 characters.",
     * )
     * @var integer
     */
    private $expYear;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max="3",
     *     min="3",
     *     maxMessage="CVC must contain maximum 3 characters.",
     *     minMessage="CVC must contain minimum 3 characters.",
     * )
     * @var integer
     */
    private $cvc;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLast4(): ?int
    {
        return $this->last4;
    }

    public function setLast4(int $last4): self
    {
        $this->last4 = $last4;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCardId(): ?string
    {
        return $this->cardId;
    }

    public function setCardId(string $cardId): self
    {
        $this->cardId = $cardId;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getExpMonth(): ?string
    {
        return $this->expMonth;
    }

    public function setExpMonth(string $expMonth): self
    {
        $this->expMonth = $expMonth;

        return $this;
    }

    public function getExpYear(): ?string
    {
        return $this->expYear;
    }

    public function setExpYear(string $expYear): self
    {
        $this->expYear = $expYear;

        return $this;
    }

    public function getCvc(): ?string
    {
        return $this->cvc;
    }

    public function setCvc(string $cvc): self
    {
        $this->cvc = $cvc;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'last4' => $this->getLast4(),
            'card_id' => $this->getCardId(),
            'user' => $this->getUser(),
        ];
    }
}
