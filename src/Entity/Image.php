<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={"pagination_items_per_page"=30},
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
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "nom": "ipartial", "taille": "ipartial"})
 * @ApiFilter(OrderFilter::class, properties={"nom"}, arguments={"orderParameterName"="order"})
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @Vich\Uploadable
 */
class Image
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
     *
     * @var string|null
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     * @Groups("get")
     *
     * @var int|null
     */
    private $taille;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="produit_image", fileNameProperty="nom", size="taille")
     *
     * @var File|null
     */
    private $imageFile;

    /**
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

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

    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function setTaille(?int $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getNom();
    }
}
