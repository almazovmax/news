<?php
namespace NewsBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class Pagination
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function paginator(Request $request, $entity)
    {

        $qb = $this->em
            ->getRepository('NewsBundle:'.$entity)
            ->createQueryBuilder('u');

        $query = $qb
            ->orderBy('u.id', 'DESC')
            ->getQuery();

        return $query;
    }
}