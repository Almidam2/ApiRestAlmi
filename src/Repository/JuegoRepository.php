<?php

namespace App\Repository;

use App\Entity\Juego;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Juego>
 */
class JuegoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Juego::class);
    }
    public function getProduct(/*$juego*/){
        $query = $this->getEntityManager()->createQuery(
            //'SELECT  p FROM App\Entity\Producto p INNER JOIN App\Entity\Juego j where p.id=j.producto'
            'SELECT p FROM App\Entity\Producto p INNER JOIN App\Entity\Juego j with p.id = j.producto'
        );

        // Ejecuta la consulta y devuelve el resultado
        return $query->getResult();
}
public function getProductbyName(String $name){
    $query = $this->getEntityManager()->createQuery(
        //'SELECT  p FROM App\Entity\Producto p INNER JOIN App\Entity\Juego j where p.id=j.producto'
        'SELECT p FROM App\Entity\Producto p INNER JOIN App\Entity\Juego j with p.id = j.producto where p.nombre Like :name'
    );
    $query->setParameter('name','%'.$name.'%');
    // Ejecuta la consulta y devuelve el resultado
    return $query->getResult();
}


    //    /**
    //     * @return Juego[] Returns an array of Juego objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Juego
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
