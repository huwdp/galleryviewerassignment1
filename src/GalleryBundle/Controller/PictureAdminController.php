<?php

namespace GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GalleryBundle\Entity\Picture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


use GalleryBundle\Form\PictureType;

class PictureAdminController extends Controller
{
	public function IsLoggedOn(Request $request)
	{
		$session = $request->getSession();
		if ($session->get('uid') !== NULL)
		{
			return true;
		}
		return false;
	}
	
    public function indexAction(Request $request)
    {
		if (!$this->IsLoggedOn($request))
			return $this->redirect($this->generateUrl('gallery_admin_login'));
		
		$menu = array(
			"Home" => $this->generateUrl('gallery_admin_homepage'),
			"Add Picture" => $this->generateUrl('gallery_admin_add_picture'),
			"Logout" => $this->generateUrl('gallery_admin_logout')
		);
		
		$repository = $this->getDoctrine()->getRepository('GalleryBundle:Picture');
        $pictures = $repository->findAll();
        return $this->render('GalleryBundle:Admin:index.html.twig', array('menu' => $menu, 'pictures' => $pictures));
    }
    
    public function addPictureAction(Request $request)
    {
		if (!$this->IsLoggedOn($request))
			return $this->redirect($this->generateUrl('gallery_admin_login'));
		
		$menu = array(
			"Home" => $this->generateUrl('gallery_admin_homepage'),
			"Add Picture" => $this->generateUrl('gallery_admin_add_picture'),
			"Logout" => $this->generateUrl('gallery_admin_logout')
		);
		
		$picture = new Picture();
		$form = $this->createForm(new PictureType(), $picture, array(
			'em' => $this->getDoctrine()->getManager(),
		))
		->add('filename', 'file')
		->add('save', 'submit', array('label' => 'Add Picture'))
		;
		
		$picture->setTimestamp(new \DateTime('now'));
		$form->handleRequest($request);
		$session = $request->getSession();
		
		if ($request->getMethod() == 'POST')
		{
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$picture = $form->getData();
				$session = $request->getSession();
				$picture->setUserid($session->get('uid'));
				$picture->setCategory(1);	// Could not get Transformer working
				if ($picture->upload())
				{
					$em->persist($picture);
					$em->flush();
					$session->getFlashBag()->add('success', 'Picture added.');
				}
				else
				{
					$session->getFlashBag()->add('error', 'Could not upload picture.');
				}
			}
			else
			{
				$session->getFlashBag()->add('error', 'Data submitted not valid.');
			}
		}

		return $this->render('GalleryBundle:Admin:add.html.twig', array('menu' => $menu, 'form' => $form->createView()));
	}
	
	public function editPictureAction(Request $request, $id)
	{
		if (!$this->IsLoggedOn($request))
			return $this->redirect($this->generateUrl('gallery_admin_login'));
		
		$menu = array(
			"Home" => $this->generateUrl('gallery_admin_homepage'),
			"Add Picture" => $this->generateUrl('gallery_admin_add_picture'),
			"Logout" => $this->generateUrl('gallery_admin_logout')
		);
		
		$repository = $this->getDoctrine()->getRepository('GalleryBundle:Picture');
		$picture = $repository->findOneBy(
			array('id' => $id)
		);
		
		if ($picture === null)
		{
			return new Response('Could not find picture.');
		}
		
		$form = $this->createForm(new PictureType(), $picture, array(
			'em' => $this->getDoctrine()->getManager(),
		))
		->add('save', 'submit', array('label' => 'Edit Picture'))
		;		
		
		$form->handleRequest($request);
		$session = $request->getSession();
		
		if ($request->getMethod() == 'POST')
		{
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$picture = $form->getData();
				$session = $request->getSession();
				$picture->setUserid($session->get('uid'));
				$picture->setCategory(1);	// Could not get Transformer working
				
				$em->persist($picture);
				$em->flush();
				$session->getFlashBag()->add('success', 'Picture updated.');
			}
			else
			{
				$session->getFlashBag()->add('error', 'Data submitted not valid.');
			}
		}
		
		return $this->render('GalleryBundle:Admin:edit.html.twig', array('menu' => $menu, 'form' => $form->createView(), 'picture' => $picture));
	}
	
	public function removePictureAction(Request $request, $id)
	{
		if (!$this->IsLoggedOn($request))
			return $this->redirect($this->generateUrl('gallery_admin_login'));
		
		$menu = array(
			"Home" => $this->generateUrl('gallery_admin_homepage'),
			"Add Picture" => $this->generateUrl('gallery_admin_add_picture'),
			"Logout" => $this->generateUrl('gallery_admin_logout')
		);
		
		$product = $this->getDoctrine()->getRepository('GalleryBundle:Picture')->find($id);

		if (!$product)
			throw $this->createNotFoundException('No product found for id '.$id);

		$em = $this->getDoctrine()->getManager();
		$em->remove($product);
		$em->flush();

		$removed = true;
		
		$session = $request->getSession();
		$session->getFlashBag()->add('success', 'Picture removed.');
		
		return $this->render('GalleryBundle:Admin:remove.html.twig',array('menu' => $menu));
	}
}
