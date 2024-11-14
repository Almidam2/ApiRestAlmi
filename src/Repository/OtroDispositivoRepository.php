<?php

namespace App\Repository;

use App\Entity\OtroDispositivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OtroDispositivo>
 */
class OtroDispositivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OtroDispositivo::class);
    }

    public function getProduct(/*$juego*/){
        $query = $this->getEntityManager()->createQuery(
            'SELECT  p FROM App\Entity\Producto p INNER JOIN App\Entity\OtroDispositivo o with p.id=o.producto'
        );
    
        // Ejecuta la consulta y devuelve el resultado
        return $query->getResult();
    
    
    }
}


    //    /**
    //     * @return OtroDispositivo[] Returns an array of OtroDispositivo objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?OtroDispositivo
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

