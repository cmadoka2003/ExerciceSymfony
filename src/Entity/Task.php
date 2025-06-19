<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
/**
 *  Indique que cette classe est une entité Doctrine liée à la table "task".
 *  Elle utilisera le repository "TaskRepository" pour les requêtes personnalisées.
 */
class Task
{
    /**
     * Identifiant unique de la tâche
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Titre de la tâche
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * Description de la tâche
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * Statut de la tâche (true = terminée, false = en cours)
     *
     * @var bool|null
     */
    #[ORM\Column]
    private ?bool $statut = null;

    // --------- GETTERS & SETTERS --------- //

    /**
     * Retourne l'identifiant de la tâche
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le titre de la tâche
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Définit le titre de la tâche
     *
     * @param string $title
     * @return static
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Retourne la description de la tâche
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description de la tâche
     *
     * @param string $description
     * @return static
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Retourne le statut de la tâche
     *
     * @return bool|null
     */
    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    /**
     * Définit le statut de la tâche
     *
     * @param bool $statut
     * @return static
     */
    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;
        return $this;
    }
}
