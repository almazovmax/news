<?php
namespace NewsBundle\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return parent::findOneBy($criteria, $orderBy);
    }


    public function updateRole($user)
    {
        $q = $this->createQueryBuilder('u')
            ->update('NewsBundle:User', 'u')
            ->set('u.role', '?1')
            ->setParameter(1, $user->getRole())
            ->where('u.id = ?2')
            ->setParameter(2, $user->getId())
            ->getQuery();
        $q -> execute();
    }
}