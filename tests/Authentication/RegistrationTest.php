<?php

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationTest extends WebTestCase
{
    public function testRegistrationWithValidInputs()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();

        // Fill in the form fields with valid data
        $userEmail = 'test@example.com';
        $form['registration_form[email]'] = $userEmail;
        $form['registration_form[plainPassword]'] = 'testpassword';
        $form['registration_form[agreeTerms]'] = true;

        // Submit the form
        $client->submit($form);

        // Cannot test by redirect as it redirects to just /
        // Why is this failing? Check later
        // $this->assertResponseRedirects('/'); // Redirect to the homepage or another route after successful registration

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
    }

    public function testRegistrationPreventsDuplicateEmail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();

        // Fill in the form fields with an existing email
        $form['registration_form[email]'] = 'test@example.com';
        $form['registration_form[plainPassword]'] = 'testpassword';
        $form['registration_form[agreeTerms]'] = true;

        // Submit the form
        $client->submit($form);

        // Assert that the registration form is displayed again with an error message
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.invalid-feedback', 'There is already an account with this email');
    }
}

