<?php

use App\Entity\Answer;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class VoteTest extends WebTestCase
{
    public function testVoteUp()
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
    }

    public function testVoteDown()
    {
        $client = static::createClient();

        // Log in or register a user (depending on your authentication setup)
        // Load login page
        $crawler = $client->request('GET', 'http://localhost:8000/login');

        // Fill in the login form with valid credentials
        $form = $crawler->selectButton('Sign in')->form();
        $form['email'] = 'test@example.com';
        $form['password'] = 'wellcome1300';

        // Submit the form
        $client->submit($form);

        // Get the question ID from the database or another source
        $questionSlug = 'sample-question';

        // Get vote count before voting
        $questionRepository = $client->getContainer()->get('doctrine')->getRepository(Question::class);
        $questionBefore = $questionRepository->findOneBy(['slug' => $questionSlug]);
        $voteCountBefore = $questionBefore->getVotes();

        // Send vote up request
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
