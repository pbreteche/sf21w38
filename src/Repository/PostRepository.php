<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findByMonth(\DateTimeImmutable $month)
    {
        $start = $month->modify('first day of this month midnight');
        $end =$start->modify('+1 month');

        return $this->getEntityManager()->createQuery(
            'SELECT p, author FROM '.Post::class.' p'.
            ' WHERE p.createdAt >= :start'.
            ' LEFT JOIN p.writtenBy author'.
            ' AND p.createdAt < :end'.
            ' ORDER BY p.createdAt DESC'
        )->setParameters([
            'start' => $start,
            'end' => $end,
        ])
            //->setMaxResults(4)
            //->setFirstResult(4)
            ->getResult()
        ;
    }

    /**
     * @return Post[]
     */
    public function findByMonth2(\DateTimeImmutable $month, int $page = 1, bool $stop= false)
    {
        if (1 > $page) {
            $page = 1;
        }

        $start = $month->modify('first day of this month midnight');
        $end =$start->modify('+1 month');

        $queryBuilder= $this->createQueryBuilder('post')
            ->leftJoin('post.writtenBy', 'author')
            ->addSelect('author')
            ->andWhere('post.createdAt >= :start')
        ;

        if ($stop) {
            $queryBuilder
                ->andWhere('post.createdAt < :end')
                ->setParameter('end', $end)
            ;
        }

        return $queryBuilder
            ->getQuery()
            ->setParameters([
                'start' => $start,
            ])
            ->setMaxResults(20)
            ->setFirstResult(20 * ($page -1))
            ->getResult()
        ;
    }

    public function createQueryBuilder($alias, $indexBy = null)
    {
        return parent::createQueryBuilder($alias, $indexBy)
            //->andWhere('post.isPublished = 1')
            ->orderBy('post.createdAt', 'DESC')
            ;
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
