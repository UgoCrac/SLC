<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getDevis"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getDevis"])]
    #[Assert\NotBlank(message: 'L\'adresse est obligatoire')]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["getDevis"])]
    #[Assert\NotBlank(message: 'La date est obligatoire')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["getDevis"])]
    private ?\DateTimeInterface $accepte = null;

    #[ORM\ManyToOne(targetEntity: Clients::class, inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getDevis"])]
    #[Assert\NotBlank(message: 'L\'id client est obligatoire')]
    private ?Clients $client;

    #[ORM\OneToMany(mappedBy: 'devis', targetEntity: DevisMateriaux::class)]
    #[Groups(["getDevis"])]
    private Collection $devisMateriauxes;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getDevis"])]
    private ?string $nbreHeure = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getDevis"])]
    private ?string $tauxHoraire = null;

    public function __construct()
    {
        $this->devisMateriauxes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getAccepte(): ?\DateTimeInterface
    {
        return $this->accepte;
    }

    public function setAccepte(?\DateTimeInterface $accepte): static
    {
        $this->accepte = $accepte;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(Clients $client): static
    {
        $this->client = $client;

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
            $devisMateriaux->setDevis($this);
        }

        return $this;
    }

    public function removeDevisMateriaux(DevisMateriaux $devisMateriaux): static
    {
        if ($this->devisMateriauxes->removeElement($devisMateriaux)) {
            // set the owning side to null (unless already changed)
            if ($devisMateriaux->getDevis() === $this) {
                $devisMateriaux->setDevis(null);
            }
        }

        return $this;
    }

    public function getNbreHeure(): ?string
    {
        return $this->nbreHeure;
    }

    public function setNbreHeure(?string $nbreHeure): static
    {
        $this->nbreHeure = $nbreHeure;

        return $this;
    }

    public function getTauxHoraire(): ?string
    {
        return $this->tauxHoraire;
    }

    public function setTauxHoraire(?string $tauxHoraire): static
    {
        $this->tauxHoraire = $tauxHoraire;

        return $this;
    }
}
