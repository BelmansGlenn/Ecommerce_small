<?php

namespace App\Classes;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = "145dee4e294b74c37a6bd3709e6a77e2";

    private $secret_key = "3aca4dbb2dd8bbbdb1400865b3859b87";

    public function send($to_email, $to_name, $subject, $title, $content, $url, $button)
    {
        $mj = new Client($this->api_key, $this->secret_key,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "Glaira.eshop@gmail.com",
                        'Name' => "Glaira"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3295032,
                    'TemplateLanguage' => true,
                    'Subject' =>$subject,
                    'Variables' => [
                        'title' => $title,
                        'content' => $content,
                        'url' => $url,
                        'button' => $button
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

}