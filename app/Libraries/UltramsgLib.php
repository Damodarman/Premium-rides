<?php
namespace App\Libraries;
use UltraMsg\WhatsAppApi;

use App\Models\UltramsgLibConfigModel;

class UltramsgLib
{
    private $client; // Declare $client as a class property

    public function __construct()
    {
        $session = session();
        $fleet_id = $session->get('fleet_id');
		if($fleet_id == null){
			$fleet_id = 1;
		}

        // Load Ultramsg configuration from the database
        $configModel = new UltramsgLibConfigModel();
        $configData = $configModel->where('fleet_id', $fleet_id)->findAll();

        if (!empty($configData)) {
            $ultramsg_token = $configData[0]['Token'];
            $instance_id = $configData[0]['Instance_ID'];
            $this->client = new WhatsAppApi($ultramsg_token, $instance_id); // Store it as a class property
        } else {
            // Handle the case when no configuration is found
        }
    }
	
	public function checkContact($to){
		$check = $this->client->checkContact($to);
		return($check);
	}
		
	public function sendImg($data){
		$to = $data['to'];
		$image = $data['image'];
		$caption = $data['caption'];
		$sendimg = $this->client->sendImageMessage($to,$image,$caption);
		return $sendimg;
		
	}

    public function sendMsg($data)
    {
        $to = $data['to'];
        $body = $data['msg'];
        $check = $this->client->checkContact($to); // Access $client using $this
		if($check['status'] == 'valid'){
			$api = $this->client->sendChatMessage($to, $body); // Access $client using $this
			$data['api'] = $api;
			$data['to'] = $to;
			$data['status'] = 'success';
			return($data);
			//print_r($api);
		}else{
			$data['to'] = $to;
			$data['status'] = 'error';
			$data['error'] = 'invalid';
			return($data);
		}
			
    }
}
