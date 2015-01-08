<?php

namespace Autoformation\BlogBundle\Controller;

use Autoformation\BlogBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Httpfoundation\Response;
use Autoformation\BlogBundle\Entity\Image;
use Autoformation\BlogBundle\Entity\Commentaire;

class BlogController extends Controller
{
    public function indexAction($page)
    {
        // On ne sait pas combien de pages il y a
        // Mais on sait qu'une page doit être supérieure ou égale à 1
        if( $page < 1 )
        {
            // On déclenche une exception NotFoundHttpException
            // Cela va afficher la page d'erreur 404 (on pourra personnaliser cette page plus tard d'ailleurs)
            throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
        }
        // Ici, on récupérera la liste des articles, puis on la passera au template
        // Mais pour l'instant, on ne fait qu'appeler le template
        // Les articles :
        $articles = array(
            array(
                'titre' => 'Mon weekend a Phi Phi Island !',
                'id' => 1,
                'auteur' => 'winzou',
                'contenu' => 'Ce weekend était trop bien. Blabla…',
                'date' => new \Datetime()
            ),
            array(
                'titre' => 'Repetition du National Day de Singapour',
                'id' => 2,
                'auteur' => 'winzou',
                'contenu' => 'Bientôt prêt pour le jour J. Blabla…',
                'date' => new \Datetime()
            ),
            array(
                'titre' => 'Chiffre d\'affaire en hausse',
                'id' => 3,
                'auteur' => 'M@teo21',
                'contenu' => '+500% sur 1 an, fabuleux. Blabla…',
                'date' => new \Datetime()
            )
        );

        return $this->render('AutoformationBlogBundle:Blog:index.html.twig', array('articles' => $articles));
    }
    public function voirAction($id)
    {
        $article = $this->getDoctrine()
        				->getManager()
        				->getRepository("Autoformation\BlogBundle\Entity\Article")
        				->find($id);
        if($article === null) {
        	throw $this->createNotFoundException("Article [id=$id] introuvable !!!");
        }
        $listeCommentaires = $this->getDoctrine()
	        				->getManager()
	        				->getRepository("Autoformation\BlogBundle\Entity\Commentaire")
	        				->findAll();
        
        return $this->render('AutoformationBlogBundle:Blog:voir.html.twig', array(
        																		  	'article'           => $article,
        																			'listeCommentaires' => $listeCommentaires
                                                                                  ));
    }
    public function ajouterAction()
    {
        $article = new Article();
        $article->setTitre("Mon voyage au Mali");
        $article->setAuteur("Oumar KONATE");
        $article->setContenu("Mon voyage au Mali était une très belle expérience. J'ai pu me ressourcer auprès du paysage magnifique");
        $article->setDate(new \DateTime());
        
        $image = new Image();
        $image->setUrl("http://uploads.siteduzero.com/icones/478001_479000/478657.png");
        $image->setAlt("Loger Au Mali");
        $image->setDate(new \DateTime());
        $article->setImage($image);

        $commentaire1 = new Commentaire();
        $commentaire1->setAuteur("Fan Blogueur");
        $commentaire1->setContenu("J'aime bien votre article!");
        $commentaire1->setArticle($article);
        
        $commentaire2 = new Commentaire();
        $commentaire2->setAuteur("Loulou");
        $commentaire2->setContenu("Vivement vos prochains articles ...");
        $commentaire2->setArticle($article);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($article);
        $entityManager->persist($commentaire1);
        $entityManager->persist($commentaire2);
        $entityManager->flush();

        // La gestion d'un formulaire est particulière, mais l'idée est la suivante :
        if( $this->get('request')->getMethod() == 'POST' ) {
            // Ici, on s'occupera de la création et de la gestion du formulaire
            $this->get('session')->getFlashBag()->add('notice', 'Article bien enregistré');
            // Puis on redirige vers la page de visualisation de cet article
            return $this->redirect( $this->generateUrl('autoformation_blog_voir', array('id' => $article->getId())) );
        }
        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('AutoformationBlogBundle:Blog:ajouter.html.twig', array('article' => $article));
    }
    public function modifierAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository("Autoformation\BlogBundle\Entity\Article")
        						 ->find($id);
        if($article === null) {
        	throw $this->createNotFoundException("Article [id=$id] introuvable !!!");
        }
        
        $article->setAuteur("Abdoulaye KONATE");
        $article->setTitre("Ma vision sur la vie");
        $article->setContenu("La simplicite est la cle de la vie. A bon entendeur, salut !!!");
        
        $entityManager->flush();
        
        return $this->render('AutoformationBlogBundle:Blog:modifier.html.twig', array('article' => $article));
    }
    public function supprimerAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository("Autoformation\BlogBundle\Entity\Article")
        						 ->find($id);
        if($article === null) {
        	throw $this->createNotFoundException("Article [id=$id] introuvable !!!");
        }
        $entityManager->remove($article);
        $entityManager->flush();
        return $this->render('AutoformationBlogBundle:Blog:supprimer.html.twig');
    }
    public function menuAction($nombre) // Ici, nouvel argument $nombre, on l'a transmis via le render() depuis la vue
    {
        // On fixe en dur une liste ici, bien entendu par la suite on la récupérera depuis la BDD !
        // On pourra récupérer $nombre articles depuis la BDD,
        // avec $nombre un paramètre qu'on peut changer lorsqu'on appelle cette action
        $liste = array(
            array('id' => 2, 'titre' => 'Mon dernier weekend !'),
            array('id' => 5, 'titre' => 'Sortie de Symfony2.1'),
            array('id' => 9, 'titre' => 'Petit test')
        );
        return $this->render('AutoformationBlogBundle:Blog:menu.html.twig', array(
            'liste_articles' => $liste // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaires au template !
        ));
    }
}
