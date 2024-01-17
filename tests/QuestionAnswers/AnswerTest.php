<?php

use App\Entity\Answer;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AnswerTest extends WebTestCase
{
    public function testSubmitAnswerToQuestion()
    {
        $client = static::createClient();

        // Log in or register a user (depending on your authentication setup)
        // Load login page
        $crawler = $client->request('GET', '/login');

        // Fill in the login form with valid credentials
        $form = $crawler->selectButton('Sign in')->form();
        $form['email'] = 'test@example.com';
        $form['password'] = 'testpassword';

        // Submit the form
        $client->submit($form);

        // Create a new question
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

        // Answer
        $crawler = $client->request('GET', '/answer/new/' . $questionSlug);
        $form = $crawler->selectButton('Save')->form();

        // Fill in the answer form fields with valid data
	
        $uniqueIdentifier = uniqid('sample-answer-');
	$formAnswer = 'This is a sample answer from PHPUnit stamped ' . $uniqueIdentifier . '.';
        $form['answer_form[content]'] = $formAnswer;

        // Submit the form
        $client->submit($form);

        $this->assertResponseRedirects('/questions/' . $questionSlug); // Redirect to the question page after successful answer submission

        // Assert that the answer is created in the database
        $answerRepository = $client->getContainer()->get('doctrine')->getRepository(Answer::class);
        $createdAnswer = $answerRepository->findOneBy(['content' => $formAnswer]);

        $this->assertInstanceOf(Answer::class, $createdAnswer);
        $this->assertSame($createdAnswer->getQuestion()->getSlug(), $questionSlug);
    }
}
