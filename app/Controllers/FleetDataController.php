<?php

namespace App\Controllers;

use App\Libraries\OAuth2Client;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Exceptions\HTTPException;

class FleetDataController extends ResourceController
{
    public function getFleetData()
    {
        $oauthClient = new OAuth2Client();

        $httpClient = service('curlrequest');

        try {
            $response = $httpClient->get(
                'https://example-api.com/fleet-data', // Replace with actual endpoint
                [
                    'headers' => $oauthClient->getAuthorizationHeader(),
                ]
            );

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                return $this->respond($data);
            }

            return $this->fail(
                'Failed to fetch fleet data.',
                $response->getStatusCode()
            );
        } catch (HTTPException $e) {
            return $this->fail(
                'Error fetching data: ' . $e->getMessage(),
                ResponseInterface::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
	
	public function getCompanies()
    {
        // Step 3: Get OAuth2 Client
        $oauthClient = new OAuth2Client();

        // Step 4: Create HTTP Client
        $httpClient = service('curlrequest');

        // Step 5: Make GET Request
        try {
            $response = $httpClient->get(
                'https://node.bolt.eu/fleet-integration-gateway/fleetIntegration/v1/getCompanies',
                [
                    'headers' => array_merge(
                        ['accept' => 'application/json'],
                        $oauthClient->getAuthorizationHeader() // Fetch the OAuth2 token
                    ),
                ]
            );

            // Step 6: Handle Response
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                return $this->respond($data); // Return data in JSON format
            }

            return $this->fail(
                'Failed to fetch company data.',
                $response->getStatusCode()
            );
        } catch (\Exception $e) {
            return $this->fail(
                'Error during request: ' . $e->getMessage(),
                ResponseInterface::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
	
	
public function getFleetDataStateLogs()
    {
        // Step 3: Get OAuth2 Client
        $oauthClient = new OAuth2Client();

        // Step 4: Create HTTP Client
        $httpClient = service('curlrequest');

        // Step 5: Define the Request Data
        $requestData = [
            'company_id' => 73676, // Use your company ID
            'start_ts' => 0,      // Customize as needed
            'end_ts' => 0,        // Customize as needed
            'offset' => 0,
            'limit' => 0,
        ];

        try {
            // Step 6: Make POST Request with Authorization and JSON Data
            $response = $httpClient->post(
                'https://node.bolt.eu/fleet-integration-gateway/fleetIntegration/v1/getFleetStateLogs',
                [
                    'headers' => array_merge(
                        [
                            'accept' => 'application/json',
                            'Content-Type' => 'application/json'
                        ],
                        $oauthClient->getAuthorizationHeader() // Fetch the OAuth2 token
                    ),
                    'json' => $requestData, // Data to be sent in the request body
                ]
            );

            // Step 7: Handle Response
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                return $this->respond($data); // Return data in JSON format
            }

            return $this->fail(
                'Failed to fetch fleet data.',
                $response->getStatusCode()
            );
        } catch (\Exception $e) {
            return $this->fail(
                'Error during request: ' . $e->getMessage(),
                ResponseInterface::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }	
	
}
