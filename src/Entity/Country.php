<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[Groups(['post:read:collection'])]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  #[Groups(['post:read:collection'])]
  private ?string $name = null;

  #[ORM\OneToMany(mappedBy: 'country', targetEntity: Post::class)]
  private Collection $posts;

  public function __construct()
  {
    $this->posts = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  /**
   * @return Collection<int, Post>
   */
  public function getPosts(): Collection
  {
    return $this->posts;
  }

  public function addPost(Post $post): static
  {
    if (!$this->posts->contains($post)) {
      $this->posts->add($post);
      $post->setCountry($this);
    }

    return $this;
  }

  public function removePost(Post $post): static
  {
    if ($this->posts->removeElement($post)) {
      // set the owning side to null (unless already changed)
      if ($post->getCountry() === $this) {
        $post->setCountry(null);
      }
    }

    return $this;
  }
}
