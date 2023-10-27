<?php

namespace App\Entity;

use App\Repository\MateriauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: MateriauxRepository::class)]
class Materiaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getDevisMateriaux"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getDevisMateriaux"])]
    #[Assert\NotBlank(message: 'Le nom du matériaux est obligatoire')]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getDevisMateriaux"])]
    #[Assert\NotBlank(message: 'L\unité est obligatoire')]
    private ?string $unite = null;

    #[ORM\OneToMany(mappedBy: 'materiaux', targetEntity: DevisMateriaux::class)]
    private Collection $devisMateriauxes;

    #[ORM\Column]
    #[Groups(["getMateriaux"])]
    private ?bool $supprime = false;

    public function __construct()
    {
        $this->devisMateriauxes = new ArrayCollection();
    }

    public function isSupprime(): ?bool
    {
        return $this->supprime;
    }

    public function setSupprime(bool $supprime): static
    {
        $this->supprime = $supprime;

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(string $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    /**
     * @return Collection<int, DevisMateriaux>
     */
    public function getDevisMateriauxes(): Collection
    {
        return $this->devisMateriauxes;
    }

    public function addDevisMateriaux(DevisMateriaux $devisMateriaux): static
    {
        if (!$this->devisMateriauxes->contains($devisMateriaux)) {
            $this->devisMateriauxes->add($devisMateriaux);
            $devisMateriaux->setMateriaux($this);
        }

        return $this;
    }

    public function removeDevisMateriaux(DevisMateriaux $devisMateriaux): static
    {
        if ($this->devisMateriauxes->removeElement($devisMateriaux)) {
            // set the owning side to null (unless already changed)
            if ($devisMateriaux->getMateriaux() === $this) {
                $devisMateriaux->setMateriaux(null);
            }
        }

        return $this;
    }
}
