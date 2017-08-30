<?php

namespace AppBundle\Repository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{
    public function findForList($filter)
    {
        $qb = $this
            ->createQueryBuilder('comment')
            ->select('comment')
            ->addOrderBy('comment.publishedAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();
    }
}
