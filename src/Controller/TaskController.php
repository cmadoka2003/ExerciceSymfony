<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskForm;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur gérant les opérations CRUD pour les tâches.
 * 
 * Routes préfixées par "/task".
 */
final class TaskController extends AbstractController
{
    /**
     * Affiche la liste de toutes les tâches.
     *
     * @param TaskRepository $taskRepository Le repository pour accéder aux tâches
     * @return Response La réponse HTTP contenant la vue avec la liste des tâches
     */
    #[Route('/' ,name: 'app_task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        // Récupère toutes les tâches en base et les transmet à la vue
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    /**
     * Crée une nouvelle tâche via un formulaire.
     *
     * @param Request $request La requête HTTP
     * @param TaskRepository $taskRepository Le repository pour sauvegarder la tâche
     * @return Response La réponse HTTP avec redirection ou formulaire
     */
    #[Route('/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TaskRepository $taskRepository): Response
    {
        $task = new Task();

        // Création du formulaire lié à la nouvelle tâche
        $form = $this->createForm(TaskForm::class, $task);
        // $form->handleRequest($request);

        // // Traitement du formulaire : si soumis et valide, sauvegarde et redirection
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $task->setStatut(false);
        //     dd($task);
        //     // $taskRepository->save($task);
        //     // return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        // }
        $form->handleRequest($request);

        dd([
            'isSubmitted' => $form->isSubmitted(),
            'isValid' => $form->isValid(),
            'errors' => (string) $form->getErrors(true, false),
            'data' => $request->request->all(),
        ]);

        // Affiche le formulaire pour création
        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * Modifie une tâche existante via un formulaire.
     *
     * @param Request $request La requête HTTP
     * @param Task $task La tâche à modifier
     * @param TaskRepository $taskRepository Le repository pour sauvegarder la tâche
     * @return Response La réponse HTTP avec redirection ou formulaire
     */
    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        // Création du formulaire lié à la tâche existante
        $form = $this->createForm(TaskForm::class, $task);
        $form->handleRequest($request);

        // Si formulaire soumis et valide, on sauvegarde et redirige
        if ($form->isSubmitted() && $form->isValid()) {
            $taskRepository->save($task);
            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        // Affiche le formulaire d'édition
        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * Supprime une tâche après validation du token CSRF.
     *
     * @param Request $request La requête HTTP
     * @param Task $task La tâche à supprimer
     * @param TaskRepository $taskRepository Le repository pour supprimer la tâche
     * @return Response Redirection vers la liste des tâches
     */
    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        // Vérifie la validité du token CSRF pour la suppression
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->getPayload()->getString('_token'))) {
            $taskRepository->delete($task);
        }

        // Redirection vers la liste des tâches
        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Modifie le statut d'une tâche existante via un formulaire.
     *
     * @param Request $request La requête HTTP
     * @param Task $task La tâche à supprimer
     * @param TaskRepository $taskRepository Le repository pour supprimer la tâche
     * @return Response Redirection vers la liste des tâches
     */
    #[Route('/{id}/statut', name: 'app_task_toggle', methods: ['POST'])]
    public function toggle(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        // Vérifie la validité du token CSRF pour la changement de statut
        if ($this->isCsrfTokenValid('toggle'.$task->getId(), $request->getPayload()->getString('_token'))) {
            $task->setStatut(!$task->isStatut());
            $taskRepository->save($task);
        }

        // Redirection vers la liste des tâches
        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }
}
