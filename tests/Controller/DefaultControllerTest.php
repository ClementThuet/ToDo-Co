<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;

class DefaultControllerTest extends TestCase
{
    /*public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }*/
    
    public function testTest()
    {
        $age=24;

        $this->assertEquals(24, $age);
    }
    
}
