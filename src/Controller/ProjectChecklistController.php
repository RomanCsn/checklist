<?php

namespace App\Controller;

use App\Entity\ProjectChecklist;
use App\Form\ProjectChecklistType;
use App\Repository\ProjectChecklistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project/checklist')]
final class ProjectChecklistController extends AbstractController
{
    #[Route(name: 'app_project_checklist_index', methods: ['GET'])]
    public function index(ProjectChecklistRepository $projectChecklistRepository): Response
    {
        return $this->render('project_checklist/index.html.twig', [
            'project_checklists' => $projectChecklistRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_project_checklist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projectChecklist = new ProjectChecklist();
        $form = $this->createForm(ProjectChecklistType::class, $projectChecklist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projectChecklist);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_checklist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project_checklist/new.html.twig', [
            'project_checklist' => $projectChecklist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_project_checklist_show', methods: ['GET'])]
    public function show(ProjectChecklist $projectChecklist): Response
    {
        return $this->render('project_checklist/show.html.twig', [
            'project_checklist' => $projectChecklist,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_checklist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProjectChecklist $projectChecklist, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectChecklistType::class, $projectChecklist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_project_checklist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project_checklist/edit.html.twig', [
            'project_checklist' => $projectChecklist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_project_checklist_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectChecklist $projectChecklist, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projectChecklist->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($projectChecklist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_project_checklist_index', [], Response::HTTP_SEE_OTHER);
    }
}
