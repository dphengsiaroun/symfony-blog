<?php

namespace AppBundle\Repository;

/**
 * ContactRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContactRepository extends \Doctrine\ORM\EntityRepository
{
    public function findForList($filter)
    {
        $qb = $this
            ->createQueryBuilder('contact')
            ->select('contact')
            ->addOrderBy('contact.contactedAt', 'DESC')
        ;

        if ('only_processed' === $filter) {
            $qb->andWhere('contact.processedAt IS NOT NULL');
        }
        if ('only_not_processed' === $filter) {
            $qb->andWhere('contact.processedAt IS NULL');
        }

        return $qb->getQuery()->getResult();
    }
}
