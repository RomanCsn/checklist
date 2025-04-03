<?php

namespace App\Entity;

use App\Repository\ChecklistTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChecklistTemplateRepository::class)]
class ChecklistTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, ChecklistItemTemplate>
     */
    #[ORM\OneToMany(targetEntity: ChecklistItemTemplate::class, mappedBy: 'relation')]
    private Collection $checklistItemTemplates;

    public function __construct()
    {
        $this->checklistItemTemplates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, ChecklistItemTemplate>
     */
    public function getChecklistItemTemplates(): Collection
    {
        return $this->checklistItemTemplates;
    }

    public function addChecklistItemTemplate(ChecklistItemTemplate $checklistItemTemplate): static
    {
        if (!$this->checklistItemTemplates->contains($checklistItemTemplate)) {
            $this->checklistItemTemplates->add($checklistItemTemplate);
            $checklistItemTemplate->setRelation($this);
        }

        return $this;
    }

    public function removeChecklistItemTemplate(ChecklistItemTemplate $checklistItemTemplate): static
    {
        if ($this->checklistItemTemplates->removeElement($checklistItemTemplate)) {
            // set the owning side to null (unless already changed)
            if ($checklistItemTemplate->getRelation() === $this) {
                $checklistItemTemplate->setRelation(null);
            }
        }

        return $this;
    }
}
