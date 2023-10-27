<?php

namespace App\DataFixtures;

use App\Entity\Clients;
use App\Entity\Devis;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        // Création d'un user "normal"
        $user = new User();
        $user->setEmail("user@api.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
        $manager->persist($user);

        // Création d'un user "admin"
        $userAdmin = new User();
        $userAdmin->setEmail("admin@api.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
        $manager->persist($userAdmin);


        $listCustomers = [];

        for ($i = 0; $i < 10; $i++) {
            // Création de fake clients
            $customer = new Clients();
            $customer->setNom("Nom " . $i);
            $customer->setPrenom("Prenom " . $i);
            $customer->setAdresse("Adresse " . $i);
            $customer->setMail("Email " . $i);
            $customer->setTelephone('Tel ' . $i);
            $customer->setContact(false);

            $manager->persist($customer);
            $listCustomers[] = $customer;
        }

        $listDevis = [];
        for ($i = 0; $i < 10; $i++) {
            $devis = new Devis();
            $devis->setAdresse("Adresse " . $i);
            $devis->setDate(new DateTime());
            $devis->setClient($listCustomers[$i]);

            $manager->persist($devis);
        }



        $manager->flush();
    }
}
