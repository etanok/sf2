<?php

namespace Autoformation\BlogBundle\Controller;

use Autoformation\BlogBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Httpfoundation\Response;
use Autoformation\BlogBundle\Entity\Image;
use Autoformation\BlogBundle\Entity\Commentaire;
use Autoformation\BlogBundle\Form\ArticleType;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use JMS\SecurityExtraBundle\Annotation\Secure;

class BlogController extends Controller
{
    public function indexAction($page)
    {
        $nbrParPage = 5;
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
        $articles = $this->getDoctrine()
                            ->getManager()
                            ->getRepository("AutoformationBlogBundle:Article")
                            ->getArticles($nbrParPage, $page);
        return $this->render('AutoformationBlogBundle:Blog:index.html.twig', array(
                                                                                    'articles' => $articles,
                                                                                    'nombrePage' => ceil(count($articles)/3),
                                                                                    'page' =>$page ));
    }
    public function voirAction(Article $article)
    {
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
        if(!$this->get('security.context')->isGranted('ROLE_ADMINISTRATEUR')) {
            throw new AccessDeniedHttpException('Acces limité aux administrateurs');
        }
        $article = new Article;
        $form = $this->createForm(new ArticleType(), $article);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
                $this->getRequest()->getSession()->getFlashBag()->add('infos', 'Votre article a bien été enregistré!');
                return $this->redirect($this->generateUrl('autoformation_blog_voir', array('id' => $article->getId())));
            }
        }
        return $this->render('AutoformationBlogBundle:Blog:ajouter.html.twig',
            array(
                'form' => $form->createView(),
            ));
    }
    public function modifierAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository("Autoformation\BlogBundle\Entity\Article")
        						 ->find($id);
        if($article === null) {
        	throw $this->createNotFoundException("Article [id=$id] introuvable !!!");
        }
        $form = $this->createForm(new ArticleType(), $article);
        $request = $this->getRequest();
        if($request->getMethod() == 'POST') {
            $form->bind($request);
            if($form->isValid()) {
                $entityManager->flush();
                $this->getRequest()->getSession()->getFlashBag()->add('infos', 'Votre article a bien été modifié!');
                return $this->redirect($this->generateUrl('autoformation_blog_voir', array('id' => $article->getId())));
            }
        }
        return $this->render('AutoformationBlogBundle:Blog:ajouter.html.twig', array('form' =>$form->createView()));
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
        $this->getRequest()->getSession()->getFlashBag()->add('infos', 'Votre article a bien été supprimé!');
        return $this->redirect($this->generateUrl('autoformation_blog_accueil', array()));
    }
    public function menuAction($nombre) // Ici, nouvel argument $nombre, on l'a transmis via le render() depuis la vue
    {
        $repository = $this->getDoctrine()->getManager()->getRepository("Autoformation\BlogBundle\Entity\Article");
        $listeArticles = $repository->findBy(array(), array(), $nombre, 0);
        return $this->render('AutoformationBlogBundle:Blog:menu.html.twig', array(
            'liste_articles' => $listeArticles // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaires au template !
        ));
    }
}
                                                                                                                                                                                                                                                                     