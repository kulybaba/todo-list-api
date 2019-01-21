<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LabelRepository")
 * @UniqueEntity(fields="text", message="Email already taken")
 */
class Label implements \JsonSerializable
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
     *     max="20",
     *     min="2",
     *     maxMessage="Label must contain maximum 20 characters.",
     *     minMessage="Label must contain minimum 2 characters."
     * )
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TodoList", mappedBy="label")
     */
    private $todoLists;

    public function __construct()
    {
        $this->todoLists = new ArrayCollection();
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

    /**
     * @return Collection|TodoList[]
     */
    public function getTodoLists(): Collection
    {
        return $this->todoLists;
    }

    public function addTodoList(TodoList $todoList): self
    {
        if (!$this->todoLists->contains($todoList)) {
            $this->todoLists[] = $todoList;
            $todoList->addLabel($this);
        }

        return $this;
    }

    public function removeTodoList(TodoList $todoList): self
    {
        if ($this->todoLists->contains($todoList)) {
            $this->todoLists->removeElement($todoList);
            $todoList->removeLabel($this);
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
        ];
    }
}
