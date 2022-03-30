<?php
namespace App\Class;

use Mailjet\Client;
use Mailjet\Resources;

class Mail{
    private string $apiKey="872ef8685f1c3869ac0ac94f633566e8";
    private string $apiSecrtet="f9dcbacaaeb48dff25c174709c26b31e";

    public function send($to_email,$to_name,$subject,$content){
        $mj = new Client($this->apiKey,$this->apiSecrtet,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "narutoshippudenk2016@gmail.com",
                        'Name' => "app-webStore"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3829920,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && dd($response->getData());
    }
}