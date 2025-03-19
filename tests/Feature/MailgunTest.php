<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mailgun\Mailgun;

class MailgunTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_sending_mail_using_mailgun(): void
    {

        // Instantiate the client.
        $mg = Mailgun::create(getenv('API_KEY') ?: 'API_KEY');
        // When you have an EU-domain, you must specify the endpoint:
        // $mg = Mailgun::create(getenv('API_KEY') ?: 'API_KEY', 'https://api.eu.mailgun.net'); 

        // Compose and send your message.
        $result = $mg->messages()->send(
            'sandbox87881eb8c1f7451ebf5cb64d82d7079c.mailgun.org',
            [
                'from' => 'Mailgun Sandbox <postmaster@sandbox87881eb8c1f7451ebf5cb64d82d7079c.mailgun.org>',
                'to' => 'Beus Posta <beusposta@freemail.hu>',
                'subject' => 'Hello Beus Posta',
                'text' => 'Congratulations Beus Posta, you just sent an email with Mailgun! You are truly awesome!'
            ]
        );

        print_r($result->getMessage());
    }
}
