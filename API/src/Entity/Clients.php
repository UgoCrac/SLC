<?php

namespace App\Entity;

use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
class Clients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getClients", "getClientDevis"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getClients", "getClientDevis"])]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getClients", "getClientDevis"])]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire')]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getClients", "getClientDevis"])]
    #[Assert\NotBlank(message: "L'adresse' est obligatoire")]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getClients", "getClientDevis"])]
    #[Assert\NotBlank(message: "L'adresse mail est obligatoire")]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getClients", "getClientDevis"])]
    #[Assert\NotBlank(message: 'Le numéro de téléphone est obligatoire')]
    private ?string $telephone = null;


    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Devis::class)]
    #[Groups(["exclude"])]
    private Collection $devis;

    public function __construct()
    {
        $this->devis = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }


    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevis(Devis $devis): static
    {
        if (!$this->devis->contains($devis)) {
            $this->devis[] = $devis;
            $devis->setClient($this);
        }

        return $this;
    }

    public function removeDevis(Devis $devis, Clients $client): static
    {
        if ($this->devis->removeElement($devis)) {
            // set the owning side to null (unless already changed)
            if ($devis->getClient() === $this) {
                $devis->setClient($client);
            }
        }

        return $this;
    }
}
