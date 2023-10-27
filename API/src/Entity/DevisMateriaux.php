<?php

namespace App\Entity;

use App\Repository\DevisMateriauxRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DevisMateriauxRepository::class)]
class DevisMateriaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getDevisMateriaux"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getDevisMateriaux"])]
    #[Assert\NotBlank(message: 'La quantité est obligatoire')]
    private ?string $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'devisMateriauxes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'L\'id du devis est obligatoire')]
    private ?Devis $devis = null;

    #[ORM\ManyToOne(inversedBy: 'devisMateriauxes', cascade: ["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getDevisMateriaux"])]
    #[Assert\NotBlank(message: 'L\'id du matériaux est obligatoire')]
    private ?Materiaux $materiaux = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }


    public function getDevis(): ?Devis
    {
        return $this->devis;
    }

    // Methode pour éviter circular reference et recuperer l'id du devis dans devisMateriaux
    #[Groups(["getDevisMateriaux"])]
    public function getDevisId(): ?int
    {
        return $this->devis ? $this->devis->getId() : null;
    }

    public function setDevis(?Devis $devis): static
    {
        $this->devis = $devis;

        return $this;
    }

    public function getMateriaux(): ?Materiaux
    {
        return $this->materiaux;
    }

    public function setMateriaux(?Materiaux $materiaux): static
    {
        $this->materiaux = $materiaux;

        return $this;
    }
}
