<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TodoListRepository")
 */
class TodoList implements \JsonSerializable
{
    use Timestampable;

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
     *     maxMessage="Name must contain maximum 50 characters.",
     *     minMessage="Name must contain minimum 2 characters."
     * )
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @ORM\Column(type="string")
     */
    private $expire;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="todoLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Label", inversedBy="todoLists")
     */
    private $label;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="todoList")
     */
    private $items;

    public function __construct()
    {
        $this->label = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExpire(): ?string
    {
        return $this->expire;
    }

    public function setExpire(string $expire): self
    {
        $this->expire = $expire;

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

    /**
     * @return Collection|Label[]
     */
    public function getLabel(): Collection
    {
        return $this->label;
    }

    public function addLabel(Label $label): self
    {
        if (!$this->label->contains($label)) {
            $this->label[] = $label;
        }

        return $this;
    }

    public function removeLabel(Label $label): self
    {
        if ($this->label->contains($label)) {
            $this->label->removeElement($label);
        }

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setTodoList($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getTodoList() === $this) {
                $item->setTodoList(null);
            }
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'expire' => $this->getExpire(),
            'user' => $this->getUser(),
            'labels' => $this->getLabel(),
            'items' => $this->getItems(),
            'created' => $this->getCreated(),
            'updated' => $this->getUpdated()
        ];
    }
}
