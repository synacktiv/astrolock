<?php

namespace App\Entity;

use App\Repository\SuitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SuitRepository::class)]
class Suit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $suitName = null;

    #[ORM\Column(length: 4096, nullable: true)]
    private ?string $suitDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $suitFilename = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSuitName(): ?string
    {
        return $this->suitName;
    }

    public function setSuitName(string $suitName): self
    {
        $this->suitName = $suitName;

        return $this;
    }

    public function getSuitDescription(): ?string
    {
        return $this->suitDescription;
    }

    public function setSuitDescription(?string $suitDescription): self
    {
        $this->suitDescription = $suitDescription;

        return $this;
    }

    public function getSuitFilename(): ?string
    {
        return $this->suitFilename;
    }

    public function setSuitFilename(?string $suitFilename): self
    {
        $this->suitFilename = $suitFilename;

        return $this;
    }
}
