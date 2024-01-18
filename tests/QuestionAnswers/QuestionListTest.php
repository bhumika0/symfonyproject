<?php

use App\Entity\Question;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class QuestionListTest extends WebTestCase
{
    public function testLoadListOfQuestions()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $questionRepository = $client->getContainer()->get('doctrine')->getRepository(Question::class);
        $questionCount = $questionRepository->count([]);

	// if  questionCount > 5, pagination will occur
	if ($questionCount >=5 ) {
		$questionCount = 5;
	}
        $this->assertGreaterThanOrEqual($questionCount, $crawler->filter('.question-item')->count());
    }
}
