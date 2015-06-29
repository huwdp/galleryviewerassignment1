<?php

namespace GalleryBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/hello/Fabien');

        $this->assertTrue($crawler->filter('html:contains("Hello Fabien")')->count() > 0);
    }
    
    public function testLogin()
    {
		$client = static::createClient();

        $crawler = $client->request('GET', '/post/hello-world');
		
		$this->assertTrue($crawler->filter('html:contains("Hello Fabien")')->count() > 0);
	}
}
