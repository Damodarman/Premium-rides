<?php namespace App\Libraries\Twilio;

use Twilio\Rest\Client;

class TwilioLib {
    
    private $twilio;
    
    public function __construct()
    {
        $accountSid = 'AC93dfdb7bd151f951696fdc6e0bdc3849';
        $authToken = 'b408de344657edd4035876f5c7e505ee';
        $this->twilio = new Client($accountSid, $authToken);
    }
    
    public function sendSms($to, $message)
    {
        $from = '+12707477895';
        $this->twilio->messages->create($to, [
            'from' => $from,
            'body' => $message
        ]);
    }
    
}