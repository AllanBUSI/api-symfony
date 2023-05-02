<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    private function verif($data, $error) {
        if (!isset($data['firstname']) || strlen($data['firstname']) >= 254) {
            array_push($error, 'Le nom est incorrecte');
        }

        if (!isset($data['lastname']) || strlen($data['lastname']) >= 254) {
            array_push($error, 'Le prenom est incorrecte');
        }

        if (!isset($data['email']) || strlen($data['email']) >= 254) {
            array_push($error, 'Le email est incorrecte');
        }

        if (!isset($data['password']) || strlen($data['password']) >= 254) {
            array_push($error, 'Le password est incorrecte');
        }

        if (!isset($data['confirmPassword']) || strlen($data['confirmPassword']) >= 254) {
            array_push($error, 'Le confirmPassword est incorrecte');
        }

        if ($data['password'] != $data['confirmPassword']) {
            array_push($error, 'Le confirmPassword est incorrecte');
        }

        return $error;
    }

    public function save($data, $error)
    {
        $error = $this->verif($data, $error);

        if (count($error) > 0) {
            return [
                'error' => true,
                'messsage' => $error[0]
            ];
        }

        // $this->getEntityManager()->persist($entity);
        // $this->getEntityManager()->flush();

        return [
            'error' => false, 
            'message' => 'Votre utilisateur a bien été enregistrer'
        ];
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
