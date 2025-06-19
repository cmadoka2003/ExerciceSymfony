<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TaskControllerTest
 * 
 * Tests fonctionnels du contrôleur TaskController.
 * Utilise le repository TaskRepository pour interagir avec les entités Task en base.
 */
final class TaskControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TaskRepository $taskRepository;
    private string $path = '/task/';

    /**
     * Configuration initiale avant chaque test.
     * Initialise le client HTTP et récupère le repository Task.
     * Supprime toutes les tâches existantes pour garantir un état propre.
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        // Récupération du repository Task via le container de services Symfony
        $this->taskRepository = static::getContainer()->get(TaskRepository::class);

        // Suppression de toutes les entités Task existantes avant chaque test
        foreach ($this->taskRepository->findAll() as $task) {
            $this->taskRepository->delete($task);
        }
    }

    /**
     * Test de la page d'index affichant la liste des tâches.
     * Vérifie le code HTTP 200 et le titre de la page.
     */
    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Liste des Tâches');
    }

    /**
     * Test de la création d'une nouvelle tâche via le formulaire.
     * Vérifie que le formulaire est accessible et que la soumission redirige correctement.
     * Confirme que la tâche est bien créée en base.
     */
    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Enregistrer', [
            'task_form[title]' => 'Testing',
            'task_form[description]' => 'Testing',
        ]);

        self::assertResponseRedirects('/task');

        self::assertSame(1, $this->taskRepository->count([]));
    }

    /**
     * Test de la modification d'une tâche existante.
     * Crée une tâche, modifie ses valeurs via formulaire, puis vérifie la mise à jour en base.
     */
    public function testEdit(): void
    {
        $task = new Task();
        $task->setTitle('Value');
        $task->setDescription('Value');
        $task->setStatut(false);

        $this->taskRepository->save($task);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $task->getId()));

        $this->client->submitForm('Enregistrer', [
            'task_form[title]' => 'Something New',
            'task_form[description]' => 'Something New',
        ]);

        self::assertResponseRedirects('/task');

        $updated = $this->taskRepository->find($task->getId());

        self::assertSame('Something New', $updated->getTitle());
        self::assertSame('Something New', $updated->getDescription());
    }

    /**
     * Test de la suppression d'une tâche.
     * Crée une tâche, la supprime via formulaire, puis vérifie qu'elle n'existe plus en base.
     */
    public function testRemove(): void
    {
        $task = new Task();
        $task->setTitle('Value');
        $task->setDescription('Value');
        $task->setStatut(false);

        $this->taskRepository->save($task);

        $this->client->request('GET', '/task');
        $this->client->submitForm('Supprimer');

        self::assertResponseRedirects('/task');
        self::assertSame(0, $this->taskRepository->count([]));
    }

    /**
     * Test de la modification du statut d'une tâche.
     * Crée une tâche, la modifie via formulaire, puis vérifie la mise à jour en base.
     */
    public function testToggle(): void
    {
        $task = new Task();
        $task->setTitle('Value');
        $task->setDescription('Value');
        $task->setStatut(false);

        $this->taskRepository->save($task);

        $this->client->request('GET', '/task');
        $this->client->submitForm('En cours');

        self::assertResponseRedirects('/task');
        $updatedTask = $this->taskRepository->find($task->getId());

        self::assertTrue($updatedTask->isStatut());
    }
}