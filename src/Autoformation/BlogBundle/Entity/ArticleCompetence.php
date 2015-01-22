<?php

namespace Autoformation\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleCompetence
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ArticleCompetence
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="niveau_competence", type="string", length=255)
     */
    private $niveau;
    
    /**
     * 
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\Autoformation\BlogBundle\Entity\Article")
     */
    private $article;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\Autoformation\BlogBundle\Entity\Competence")
     */
    private $competence;

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
     * Set niveau
     *
     * @param string $niveau
     * @return ArticleCompetence
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return string 
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return ArticleCompetence
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set article
     *
     * @param \Autoformation\BlogBundle\Entity\Article $article
     * @return ArticleCompetence
     */
    public function setArticle(\Autoformation\BlogBundle\Entity\Article $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \Autoformation\BlogBundle\Entity\Article 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set competence
     *
     * @param \Autoformation\BlogBundle\Entity\Competence $competence
     * @return ArticleCompetence
     */
    public function setCompetence(\Autoformation\BlogBundle\Entity\Competence $competence)
    {
        $this->competence = $competence;

        return $this;
    }

    /**
     * Get competence
     *
     * @return \Autoformation\BlogBundle\Entity\Competence 
     */
    public function getCompetence()
    {
        return $this->competence;
    }
}
