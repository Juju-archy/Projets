<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class mail
{
    private $api_key = '9c9a76b3890aa6815b9c69c18af4641e';
    private $api_key_secret = '3097c734f061b9e819f94f4acea6fb57';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "gregory.serin@daki-suki.com",
                        'Name' => "Daki Suki"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3373990,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}