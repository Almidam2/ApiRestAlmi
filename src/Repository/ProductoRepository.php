<?php

namespace App\Repository;

use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Producto>
 */
class ProductoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Producto::class);
    }


    public function  add(Producto $u):void{
        $this->getEntityManager()->persist($u);
        $this->getEntityManager()->flush();
    }

    
    public function findByNameLike(string $name): array
    {
    // Búsqueda simple usando LIKE para encontrar productos con nombre similar
        return $this->createQueryBuilder('p')
            ->andWhere('p.nombre LIKE :name')
            ->setParameter('name', '%' . $name . '%') // El % antes y después permite encontrar coincidencias parciales
            ->orderBy('p.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }

public function findAllProductos(): array
{
    return $this->createQueryBuilder('p')
        ->orderBy('p.id', 'ASC') // Ordena los productos por ID o por el criterio que prefieras
        ->getQuery()
        ->getResult();
}


    //    /**
    //     * @return Producto[] Returns an array of Producto objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Producto
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
