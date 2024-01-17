<?php

use App\Entity\Answer;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class VoteTest extends WebTestCase
{
    public function testVote()
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

	// Test vote up
        // Get vote count before voting
        $questionRepository = $client->getContainer()->get('doctrine')->getRepository(Question::class);
        $questionBefore = $questionRepository->findOneBy(['slug' => $questionSlug]);
        $voteCountBefore = $questionBefore->getVotes();

        // Send vote up request
        // Send a direct post request for now
        // Simulate a click on /questtions/{question-slug}/vote later
        $crawler = $client->request('POST', '/questions/' . $questionSlug . '/vote', ['direction' => 'up']);

        // Assert that the question's votes have increased
        /* $question = $questionRepository->find($questionSlug); */
        $questionAfter = $questionRepository->findOneBy(['slug' => $questionSlug]);
        $voteCountAfter = $questionAfter->getVotes();

        $this->assertEquals($voteCountBefore, $voteCountAfter - 1);

	// Test Vote down
        // Get vote count before voting
        $questionRepository = $client->getContainer()->get('doctrine')->getRepository(Question::class);
        $questionBefore = $questionRepository->findOneBy(['slug' => $questionSlug]);
        $voteCountBefore = $questionBefore->getVotes();

        // Send vote down request
        // Send a direct post request for now
        // Simulate a click on /questtions/{question-slug}/vote later
        $crawler = $client->request('POST', '/questions/' . $questionSlug . '/vote', ['direction' => 'down']);

        // Assert that the question's votes have increased
        /* $question = $questionRepository->find($questionSlug); */
        $questionAfter = $questionRepository->findOneBy(['slug' => $questionSlug]);
        $voteCountAfter = $questionAfter->getVotes();

        $this->assertEquals($voteCountBefore, $voteCountAfter + 1);
    }
}
