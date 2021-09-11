<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\Groups;

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
 *          "put"={"security"="is_granted('ROLE_ADMIN')", "security_message"="Vous n'etes pas autorises à effectuer cette action"},
 *          "delete"={"security"="is_granted('ROLE_ADMIN')", "security_message"="Vous n'etes pas autorises à effectuer cette action"},
 *          "patch"={"security"="is_granted('ROLE_ADMIN')", "security_message"="Vous n'etes pas autorises à effectuer cette action"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "nom": "ipartial", "type": "ipartial"})
 * @ApiFilter(OrderFilter::class, properties={"nom"}, arguments={"orderParameterName"="order"})
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("get")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("get")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("get")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups("get")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("get")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Commande::class, mappedBy="produit", orphanRemoval=true)
     */
    private $commandes;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     * @Groups("get")
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=Menu::class, mappedBy="composition")
     */
    private $menus;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->menus = new ArrayCollection();
    }

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setProduit($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getProduit() === $this) {
                $commande->setProduit(null);
            }
        }

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Menu[]
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->addComposition($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeComposition($this);
        }

        return $this;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->nom;
    }
}
