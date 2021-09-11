<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ApiResource(
 *     attributes={"pagination_items_per_page"=30},
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
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "nom": "ipartial"})
 * @ApiFilter(OrderFilter::class, properties={"nom"}, arguments={"orderParameterName"="order"})
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
}
