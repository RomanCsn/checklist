<?php

namespace App\Controller;

use App\Entity\ProjectChecklist;
use App\Entity\ProjectChecklistItem;
use App\Form\ProjectChecklistItemType;
use App\Repository\ProjectChecklistItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project/checklist/item')]
final class ProjectChecklistItemController extends AbstractController
{
    #[Route(name: 'app_project_checklist_item_index', methods: ['GET'])]
    public function index(ProjectChecklistItemRepository $projectChecklistItemRepository): Response
    {
        return $this->render('project_checklist_item/index.html.twig', [
            'project_checklist_items' => $projectChecklistItemRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_project_checklist_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projectChecklistItem = new ProjectChecklistItem();
        $form = $this->createForm(ProjectChecklistItemType::class, $projectChecklistItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projectChecklistItem);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_checklist_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project_checklist_item/new.html.twig', [
            'project_checklist_item' => $projectChecklistItem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_project_checklist_item_show', methods: ['GET'])]
    public function show(ProjectChecklistItem $projectChecklistItem): Response
    {
        return $this->render('project_checklist_item/show.html.twig', [
            'project_checklist_item' => $projectChecklistItem,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_checklist_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProjectChecklistItem $projectChecklistItem, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectChecklistItemType::class, $projectChecklistItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_project_checklist_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project_checklist_item/edit.html.twig', [
            'project_checklist_item' => $projectChecklistItem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_project_checklist_item_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectChecklistItem $projectChecklistItem, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projectChecklistItem->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($projectChecklistItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_project_checklist_item_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/toggle', name: 'app_project_checklist_item_toggle', methods: ['POST'])]
    public function toggleChecked(ProjectChecklistItem $projectChecklistItem, EntityManagerInterface $entityManager): JsonResponse
    {
        // Inverser l'état actuel
        $projectChecklistItem->setIsChecked(!$projectChecklistItem->isChecked());
        $entityManager->flush();
        
        // Récupérer la checklist parente pour calculer la progression
        $checklist = $projectChecklistItem->getRelation();
        $total = count($checklist->getProjectChecklistItems());
        $completed = 0;
        
        foreach ($checklist->getProjectChecklistItems() as $item) {
            if ($item->isChecked()) {
                $completed++;
            }
        }
        
        $percent = $total > 0 ? round(($completed / $total) * 100) : 0;
        
        return new JsonResponse([
            'success' => true,
            'item' => [
                'id' => $projectChecklistItem->getId(),
                'isChecked' => $projectChecklistItem->isChecked()
            ],
            'progress' => [
                'completed' => $completed,
                'total' => $total,
                'percent' => $percent
            ]
        ]);
    }
}
