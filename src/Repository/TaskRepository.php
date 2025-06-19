<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * Repository personnalisé pour l'entité Task.
 * Permet d'ajouter des méthodes spécifiques de persistance ou de suppression.
 */
class TaskRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository Task
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * Enregistre une tâche (persist + flush)
     *
     * @param Task $task
     * @return void
     */
    public function save(Task $task): void
    {
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();
    }

    /**
     * Supprime une tâche de la base de données
     *
     * @param Task $task
     * @return void
     */
    public function delete(Task $task): void
    {
        $this->getEntityManager()->remove($task);
        $this->getEntityManager()->flush();
    }
}
