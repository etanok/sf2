<?php

namespace Autoformation\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Article
 *
 * @ORM\Table(name="blog_article")
 * @ORM\Entity(repositoryClass="Autoformation\BlogBundle\Entity\ArticleRepository")
 */
class Article
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
 
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     * @Assert\Length(min=5, minMessage="Le titre doit faire au moins 5 caract�res.")
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=255)
     */
    private $auteur;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;
    
    /**
     *@ORM\OneToOne(targetEntity="Autoformation\BlogBundle\Entity\Image", cascade={"persist","remove"})
     */
    private $image;

    /**
     * @var bool
     * @ORM\Column(name="etat_publication", type="boolean")
     */
    private $publication = true;

    /**
     * @ORM\OneToMany(targetEntity="Autoformation\BlogBundle\Entity\Commentaire", mappedBy="article", cascade={"remove"})
     */
    private $commentaires;
    
    /**
     * @ORM\ManyToMany(targetEntity="\Autoformation\BlogBundle\Entity\Categorie", cascade={"persist"})
     */
    private $categories;

    /**
     * Constructeur de la classe
     */
    public function __contruct()
    {
        $this->date = new \DateTime();
        $this->publication = true;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     * @return Article
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Article
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set publication
     *
     * @param boolean $publication
     * @return Article
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get publication
     *
     * @return boolean 
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * Set image
     *
     * @param \Autoformation\BlogBundle\Entity\Image $image
     * @return Article
     */
    public function setImage(\Autoformation\BlogBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Autoformation\BlogBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add commentaires
     *
     * @param \Autoformation\BlogBundle\Entity\Commentaire $commentaires
     * @return Article
     */
    public function addCommentaire(\Autoformation\BlogBundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires[] = $commentaires;
        $commentaires->setArticle($this);

        return $this;
    }

    /**
     * Remove commentaires
     *
     * @param \Autoformation\BlogBundle\Entity\Commentaire $commentaires
     */
    public function removeCommentaire(\Autoformation\BlogBundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires->removeElement($commentaires);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Add categories
     *
     * @param \Autoformation\BlogBundle\Entity\Categorie $categories
     * @return Article
     */
    public function addCategory(\Autoformation\BlogBundle\Entity\Categorie $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Autoformation\BlogBundle\Entity\Categorie $categories
     */
    public function removeCategory(\Autoformation\BlogBundle\Entity\Categorie $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @Assert\Callback
     */
    public function validerContenu(ExecutionContextInterface $contexte)
    {
        $motInterdit = array('atlassi', 'khalid');
        if (preg_match('#'.implode('|', $motInterdit).'#', $this->getContenu())) {
           $contexte->buildViolation('Interdit d\'ajouter un celibataire � mon blog')
                    ->atPath('contenu')
                    ->addViolation();
        }
    }
}
