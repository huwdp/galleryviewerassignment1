<?php

namespace GalleryBundle\Tests\Default\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;


/*
 *		Assert doesn't work with sentances, only words.
 */


class DefaultControllerTest extends WebTestCase
{
    public function testHomepage()
    {
		$client = static::createClient();
        $crawler = $client->request('GET', 'gallery');
		$this->assertGreaterThan(0,$crawler->filter('html:contains("Huw Pritchard")')->count());
	}
	
}
