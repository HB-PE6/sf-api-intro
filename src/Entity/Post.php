<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post as PostOperation;
use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
  operations: [
    new PostOperation(),
    new GetCollection(normalizationContext: [
      'groups' => ['post:read:collection']
    ]),
    new Delete(),
    new Get(),
  ]
)]
#[ApiFilter(SearchFilter::class, properties: ['country.name' => 'ipartial'])]
class Post
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[Groups(['post:read:collection'])]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  #[Groups(['post:read:collection'])]
  private ?string $title = null;

  #[ORM\Column(type: Types::TEXT, nullable: true)]
  #[Groups(['post:read:collection'])]
  private ?string $description = null;

  #[ORM\Column(type: Types::DATE_MUTABLE)]
  #[Context([
    DateTimeNormalizer::FORMAT_KEY => 'd/m/Y'
  ])]
  #[Groups(['post:read:collection'])]
  private ?\DateTimeInterface $dateCreated = null;

  #[ORM\Column(type: Types::TEXT)]
  #[Groups(['post:read:collection'])]
  private ?string $content = null;

  #[ORM\ManyToOne(inversedBy: 'posts')]
  #[Groups(['post:read:collection'])]
  private ?Country $country = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(string $title): static
  {
    $this->title = $title;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): static
  {
    $this->description = $description;

    return $this;
  }

  public function getDateCreated(): ?\DateTimeInterface
  {
    return $this->dateCreated;
  }

  public function setDateCreated(\DateTimeInterface $dateCreated): static
  {
    $this->dateCreated = $dateCreated;

    return $this;
  }

  public function getContent(): ?string
  {
    return $this->content;
  }

  public function setContent(string $content): static
  {
    $this->content = $content;

    return $this;
  }

  public function getCountry(): ?Country
  {
    return $this->country;
  }

  public function setCountry(?Country $country): static
  {
    $this->country = $country;

    return $this;
  }
}
