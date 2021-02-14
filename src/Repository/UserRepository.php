<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function all(): Query
    {
        return $this->createQueryBuilder('u')
            ->getQuery();
    }
}