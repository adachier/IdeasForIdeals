<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesRepository")
 * @Vich\Uploadable
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Articles", mappedBy="categories")
     */
    private $articles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $imgacc;

    /**
    * @Vich\UploadableField(mapping="featured_images", fileNameProperty="imgacc")
    * @var File
    */
    private $imageCat;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom;
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

    /**
     * @return Collection|Articles[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addCategory($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->removeCategory($this);
        }

        return $this;
    }

    public function getImgacc(): ?string
    {
        return $this->imgacc;
    }

    public function setImgacc(?string $imgacc): self
    {
        $this->imgacc = $imgacc;

        return $this;
    }

    public function setImageCat(File $imageacc = null)
    {
        $this->imageCat = $imageacc;
    
        if ($imageacc) {
            $this->updated_at = new \DateTime('now');
        }
    }
    
    public function getImageCat()
    {
        return $this->imageCat;
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
}
