<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ApiResource(
 *     attributes={"pagination_items_per_page"=30},
 *     normalizationContext={"groups"={"get"}},
 *     collectionOperations={
 *          "get"={"security"="is_granted('ROLE_USER')", "security_message"="Vous n'etes pas identifiés"},
 *          "post"={"security"="is_granted('ROLE_ADMIN')", "security_message"="Vous n'etes pas autorises à effectuer cette action"}
 *     },
 *     itemOperations={
 *          "get"={"security"="is_granted('ROLE_USER')", "security_message"="Vous n'etes pas identifiés"},
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'etes pas autorises à effectuer cette action"
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'etes pas autorises à effectuer cette action"
 *          },
 *          "patch"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'etes pas autorises à effectuer cette action"
 *          }
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "date": "exact"})
 * @ApiFilter(OrderFilter::class, properties={"date"}, arguments={"orderParameterName"="order"})
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity=Produit::class, inversedBy="menus")
     * @ORM\OrderBy({"type" = "ASC"})
     */
    private $composition;

    public function __construct()
    {
        $this->composition = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getComposition(): Collection
    {
        return $this->composition;
    }

    public function addComposition(Produit $composition): self
    {
        if (!$this->composition->contains($composition)) {
            $this->composition[] = $composition;
        }

        return $this;
    }

    public function removeComposition(Produit $composition): self
    {
        $this->composition->removeElement($composition);

        return $this;
    }
}
