<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class QuestionListTest extends WebTestCase
{
    public function testLoadListOfQuestions()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'http://localhost:8000/');
        var_dump($client->getResponse()->getContent());
        $this->assertGreaterThanOrEqual(1, $crawler->filter('.question-item')->count());
    }
}
