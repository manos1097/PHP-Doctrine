<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    /**
     * Raw SQL query to get a row associative array without relations
     * Exactly like normal PHP code with PDO or mysqli
     *
     * @param int $teacherId
     * @return Student[]
     */
    public function getStudentsByTeacherId(int $teacherId): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT * FROM student s
            WHERE s.teacher_id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $teacherId]);
        return $stmt->fetchAllAssociative();
    }

    /**
     * Raw DQL query to return a Student object
     *
     * @param int $id
     * @return Student
     */
    public function getStudentById(int $id): Student
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT s
            FROM App\Entity\Student s
            WHERE s.id = :id'
        );
        $query->setParameter('id', $id);
        return $query->getResult();
    }

    /**
     * Query builder example
     *
     * @return Student[] Returns an array of Student objects
     */
    /*public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }*/

    /**
     * Raw DQL query without builder
     */
    /*public function findOneById($id): ?array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('
            SELECT partial s.{id, firstName, lastName, email, address} FROM App\Entity\Student s
            WHERE s.id = :id
        ')->setParameter('id', $id);
        return $query->getResult();
    }*/

}
