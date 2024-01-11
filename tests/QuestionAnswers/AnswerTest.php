<?php

use App\Entity\Answer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AnswerTest extends WebTestCase
{
    public function testSubmitAnswerToQuestion()
    {
        $client = static::createClient();

        // Log in or register a user (depending on your authentication setup)
        // Load login page
        $crawler = $client->request('GET', 'http://localhost:8000/login');

        // Fill in the login form with valid credentials
        $form = $crawler->selectButton('Sign in')->form();
        $form['email'] = 'test@example.com';
        $form['password'] = 'testpassword';

        // Submit the form
        $client->submit($form);

        // Get the question ID from the database or another source
        $questionSlug = 'sample-question';

        $crawler = $client->request('GET', '/answer/new/' . $questionSlug);

        $form = $crawler->selectButton('Save')->form();

        // Fill in the answer form fields with valid data
        $form['answer_form[content]'] = 'This is a sample answer from PHPUnit.';

        // Submit the form
        $client->submit($form);

        $this->assertResponseRedirects('/questions/' . $questionSlug); // Redirect to the question page after successful answer submission

        // Assert that the answer is created in the database
        $answerRepository = $client->getContainer()->get('doctrine')->getRepository(Answer::class);
        $createdAnswer = $answerRepository->findOneBy(['content' => 'This is a sample answer from PHPUnit.']);

        $this->assertInstanceOf(Answer::class, $createdAnswer);
        $this->assertSame($createdAnswer->getQuestion()->getSlug(), $questionSlug);
    }
}
