<?php

namespace GalleryBundle\Tests\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;

class HomeControllerTest extends WebTestCase
{
    public function testLogin()
    {
		$client = static::createClient();
        $crawler = $client->request('GET', 'gallery');
		$this->assertGreaterThan(0,$crawler->filter('html:contains("Huw Pritchard")')->count());
	}
	
	public function testIfOnline()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', 'gallery');
		$response = $client->getResponse();
		$this->assertEquals(200, $response->getStatusCode());
	}
	
	public function loadImage()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', 'gallery/picture/1');
		$response = $client->getResponse();
		$this->assertEquals(200, $response->getStatusCode());
		$this->assertGreaterThan(0,$crawler->filter('html:contains("Dewcription")')->count());
	}
	
	public function loadWrongImage()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', 'gallery/picture/-1');
		$response = $client->getResponse();
		$this->assertGreaterThan(0,$crawler->filter('html:contains("Could not find picture.")')->count());
	}

	
}
