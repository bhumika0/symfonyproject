<?php

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

	// register
        $crawler = $client->request('GET', '/register');
        $form = $crawler->selectButton('Register')->form();

        // Fill in the form fields with valid data
        $userEmail = 'test@example.com';
        $form['registration_form[email]'] = $userEmail;
        $form['registration_form[plainPassword]'] = 'testpassword';
        $form['registration_form[agreeTerms]'] = true;

        // Submit the form
        $client->submit($form);

        // Check if the user exists in the database
        $userRepository = $client->getContainer()->get('doctrine')->getRepository(User::class);
        $createdUser = $userRepository->findOneBy(['email' => $userEmail]);
        $createdUserEmail = $createdUser->getEmail();

        $this->assertEquals($userEmail, $createdUserEmail, 'Registration of '. $userEmail . ' failed. Please check test case.');

	// if user is registered, verify it before trying to log in
	$createdUser->setIsVerified(true);

	// Save the modified user to the database
	$entityManager = $client->getContainer()->get('doctrine')->getManager();
	$entityManager->persist($createdUser);
	$entityManager->flush();
 
        // Load login page
        $crawler = $client->request('GET', '/login');

        // Fill in the login form with valid credentials
        $form = $crawler->selectButton('Sign in')->form();
        $form['email'] = 'test@example.com';
        $form['password'] = 'testpassword';

        // Submit the form
        $client->submit($form);

        //$this->assertResponseRedirects('/'); // Redirect to the homepage or another route after successful login

	// Check if the user is authenticated
        $securityContext = static::$container->get('security.authorization_checker');
        $this->assertTrue($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED'));
    }
}
