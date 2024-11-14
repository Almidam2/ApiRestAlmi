<?php

namespace App\Repository;

use App\Entity\Consola;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Consola>
 */
class ConsolaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consola::class);
    }

    public function getProduct(/*$juego*/){
        $query = $this->getEntityManager()->createQuery(
            'SELECT  p FROM App\Entity\Producto p INNER JOIN App\Entity\Consola c with p.id=c.producto'
        );

        // Ejecuta la consulta y devuelve el resultado
        return $query->getResult();


    }

    //    /**
    //     * @return Consola[] Returns an array of Consola objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Consola
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
