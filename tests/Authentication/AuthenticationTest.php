<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        // Load login page
        $crawler = $client->request('GET', 'http://localhost:8000/login');

        // Fill in the login form with valid credentials
        $form = $crawler->selectButton('Sign in')->form();
        $form['email'] = 'test@example.com';
        $form['password'] = 'testpassword';

        // Submit the form
        $client->submit($form);

        $this->assertResponseRedirects('http://localhost:8000/'); // Redirect to the homepage or another route after successful login
    }
}
