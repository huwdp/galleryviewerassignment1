<?php

namespace GalleryBundle\Tests\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;

class AdminControllerTest extends WebTestCase
{
    public function testLogin()
    {
		$client = static::createClient();

        $crawler = $client->request('GET', 'gallery/admin/login');
		$form = $crawler->selectButton('form[save]')->form();
		$form['form[username]'] = 'bob';
		$form['form[password]'] = 'bob';
		$crawler = $client->submit($form);
		$crawler = $client->followRedirect();

		
		$this->assertContains(
			'Logged in successfully.',
			$client->getResponse()->getContent()
		);
	}
	
	public function testWrongLogin()
    {
		$client = static::createClient();
        $crawler = $client->request('GET', 'gallery/admin/login');
		$form = $crawler->selectButton('form[save]')->form();
		$form['form[username]'] = 'rsdgdrgdgg';
		$form['form[password]'] = 'gdrgdrgdd';
		$crawler = $client->submit($form);
		//$crawler = $client->followRedirect();

		$this->assertContains(
			'Could not login.',
			$client->getResponse()->getContent()
		);
	}
	
}
