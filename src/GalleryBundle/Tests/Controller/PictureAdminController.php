<?php

namespace GalleryBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PictureAdminControllerTest extends WebTestCase
{
    public function testIndex()
    {
		
    }
    
    public loadEditImage()
    {
		$client = static::createClient();
        $crawler = $client->request('GET', '/gallery/admin/post/-1');
        $this->assertTrue($crawler->filter('html:contains("Image does not exist")')->count() > 0);
	}
    
    
}
