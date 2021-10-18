<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class Employee
{
    /** @see config/services.yaml */
    private string $employee;

    private EntityManagerInterface $em;

    public function __construct(string $employee, EntityManagerInterface $em)
    {
        $this->employee = $employee;
        $this->em       = $em;
    }

    /**
     * Return de l'employé
     *
     * @return User
     */
    public function getEmployee(): User
    {
        return $this->em->getRepository(User::class)->findOneBy(['email' => $this->employee]);
    }
}