<?php

namespace GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GalleryBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\QueryBuilder;



class AdminController extends Controller
{
	public function __construct()
	{
		
	}
	
    public function loginAction(Request $request)
    {
		$menu = array(
			"Home" => $this->generateUrl('gallery_homepage'),
			"Login" => $this->generateUrl('gallery_admin_homepage'),
			"Register" => $this->generateUrl('gallery_admin_register'),
		);
		
		$session = $request->getSession();
		$user = new User();
		$form = $this->createFormBuilder($user)
			->add('username', 'text')
			->add('password', 'password')
			->add('save', 'submit', array('label' => 'Login'))
			->getForm();
		$form->handleRequest($request);
		if ($request->getMethod() == 'POST')
		{
			if ($form->isValid()) {
				$repository = $this->getDoctrine()->getRepository('GalleryBundle:User');
				$user = $repository->findOneBy(
					array('username' => $form->get('username')->getData(),
					'password' => $form->get('password')->getData())
				);
				if ($user != null)
				{
					$session->set('uid', $user->GetId());					
					$session->getFlashBag()->add('success', 'Logged in successfully.');
					return $this->redirect($this->generateUrl('gallery_admin_homepage'));
				}
				else
				{
					$session->getFlashBag()->add('warning', 'Could not login.');
				}
			}
		}
		// Reset password
		//$form->get('password')->setData("");								// Empty password field
																		// before showing it to user
																		
		// Reset form
																																					
		return $this->render('GalleryBundle:Admin:login.html.twig',
			array('form' => $form->createView(), 'menu' => $menu));
    }
    
    public function registerAction(Request $request)
    {
		$menu = array(
			"Home" => $this->generateUrl('gallery_homepage'),
			"Login" => $this->generateUrl('gallery_admin_homepage'),
			"Register" => $this->generateUrl('gallery_admin_register'),
		);
		
		$repository = $this->getDoctrine()
			->getRepository('GalleryBundle:User');
		$user = new User();
		$form = $this->createFormBuilder($user)
			->add('username', 'text')
			->add('password', 'password')
			->add('email', 'text')
			->add('firstname', 'text')
			->add('surname', 'text')
			->add('dob', 'date', array('years' => range(1900, date('Y'))))
			->add('save', 'submit', array('label' => 'Register'))
			->getForm();
		$form->handleRequest($request);
		$username = $form->get('username')->getData();
		if ($request->getMethod() == 'POST')
		{
			if ($form->isValid()) {
				$user = $repository->findOneBy(
					array('username' => $username)
				);
				if ($user == null)										// Username not found
				{
					$em = $this->getDoctrine()->getManager();			// Register user to database
					$user = $form->getData();							// Change the password to hash
					$user->setCreated(new \DateTime('now'));
					$em->persist($user);
					$em->flush();
					$session->getFlashBag()->add('notice', 'Account registered.');
				}
			}
		}
		return $this->render('GalleryBundle:Admin:register.html.twig',
			array('form' => $form->createView(), 'menu' => $menu));
	}
    
    public function logoutAction()
    {
		// Clear session or cookies
		$session = $request->getSession();
		$session->remove('uid');
		$session->getFlashBag()->add('success', 'Picture added.');
		return $this->redirect($this->generateUrl('gallery_homepage'));
	}
}
