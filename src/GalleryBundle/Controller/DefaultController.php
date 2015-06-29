<?php

namespace GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GalleryBundle\Entity\Picture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

class DefaultController extends Controller
{
    public function indexAction()
    {
		$menu = array(
			"Home" => $this->generateUrl('gallery_homepage'),
			"Login" => $this->generateUrl('gallery_admin_homepage'),
			"Register" => $this->generateUrl('gallery_admin_register'),
		);
		
		//$image_link = $this->generateUrl('gallery_picture');
		$picture_link = "gallery/picture/";
		
		$repository = $this->getDoctrine()
			->getRepository('GalleryBundle:Picture');
		$pictures = $repository->findAll();
        return $this->render('GalleryBundle:Default:index.html.twig',
			array('menu' => $menu, 'pictures' => $pictures, 'picture_link' => $picture_link));
    }
    
    public function pictureAction(Request $request, $id)
    {
		$menu = array(
			"Home" => $this->generateUrl('gallery_homepage'),
			"Login" => $this->generateUrl('gallery_admin_homepage'),
			"Register" => $this->generateUrl('gallery_admin_register'),
		);
		
		$repository = $this->getDoctrine()
			->getRepository('GalleryBundle:Picture');
		$picture = $repository->findOneBy(
			array('id' => $id)
		);
		
		if ($picture === null)
		{
			return new Response('Could not find picture.');
		}
		
		return $this->render('GalleryBundle:Default:picture.html.twig',
			array('menu' => $menu, 'picture' => $picture));
	}
}
