<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max="255",
     *     min="2",
     *     maxMessage="Text must contain maximum 255 characters.",
     *     minMessage="Text must contain minimum 2 characters."
     * )
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="App\Entity\TodoList", inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $todoList;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Attachment", inversedBy="item", cascade={"persist", "remove"})
     */
    private $attachment;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean")
     */
    private $completed;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     */
    private $priority;

    public function __construct()
    {
        $this->completed = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTodoList(): ?TodoList
    {
        return $this->todoList;
    }

    public function setTodoList(?TodoList $todoList): self
    {
        $this->todoList = $todoList;

        return $this;
    }

    public function getAttachment(): ?Attachment
    {
        return $this->attachment;
    }

    public function setAttachment(?Attachment $attachment): self
    {
        $this->attachment = $attachment;

        return $this;
    }

    public function getCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'completed' => $this->getCompleted(),
            'text' => $this->getText(),
            'attachment' => $this->getAttachment(),
            'todoList' => $this->getTodoList()->getId(),
            'priority' => $this->getPriority()
        ];
    }
}
