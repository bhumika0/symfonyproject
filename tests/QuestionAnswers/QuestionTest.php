<?php

use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class QuestionTest extends WebTestCase
{
    public function testCreateQuestion()
    {
        $client = static::createClient();

        // Load login page
        $crawler = $client->request('GET', '/login');

        // Fill in the login form with valid credentials
        $form = $crawler->selectButton('Sign in')->form();
        $form['email'] = 'test@example.com';
        $form['password'] = 'testpassword';

        // Submit the form
        $client->submit($form);

        $crawler = $client->request('GET', '/questions/new');
        $form = $crawler->selectButton('Save')->form();

        // Fill in the question form fields with valid data
        $questionSlug = uniqid('sample-question-');
        $form['question_form[name]'] = 'Sample Question';
        $form['question_form[slug]'] = $questionSlug;
        $form['question_form[question]'] = 'This is a sample question content.';

        // Submit the form
        $client->submit($form);

        $this->assertResponseRedirects('/questions/' . $questionSlug); // Redirect to the homepage or another route after successful question creation

        // Assert that the question is created in the database
        $questionRepository = $client->getContainer()->get('doctrine')->getRepository(Question::class);
        $createdQuestion = $questionRepository->findOneBy(['slug' => $questionSlug]);

        $this->assertInstanceOf(Question::class, $createdQuestion);
    }
}
