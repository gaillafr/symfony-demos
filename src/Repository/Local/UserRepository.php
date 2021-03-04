<?php

declare(strict_types=1);

namespace App\Repository\Local;

use App\Entity\Local\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $localManager)
    {
        $metadata = $localManager->getClassMetadata(User::class);

        parent::__construct($localManager, $metadata);
    }
}
