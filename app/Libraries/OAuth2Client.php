<?php

namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\Exceptions\HTTPException;

class OAuth2Client
{
    private $clientId;
    private $clientSecret;
    private $tokenUrl;
    private $scope;
    private $accessToken;
    private $expiresIn;
    private $tokenType;
    private $lastFetchTime;

    public function __construct()
    {
        // Initialize with your credentials and token URL
        $this->clientId = 'KnQzL1UkEAxJpjtkbJLxU';
        $this->clientSecret = '6nwTW8Rvo1-LqIAOZVQ3SU5PzYVdJXP-Gk2CrlanESakVqIXtuqJ5eBDguez80LNKiJ9mQyTZVpTRxP1W_QWXA';
        $this->tokenUrl = 'https://oidc.bolt.eu/token';
        $this->scope = 'fleet-integration:api';
        $this->accessToken = null;
        $this->expiresIn = 600; // 10 minutes
        $this->lastFetchTime = null;
    }

    private function getHttpClient(): CURLRequest
    {
        return service('curlrequest');
    }

    private function fetchToken(): void
    {
        $httpClient = $this->getHttpClient();

        $response = $httpClient->post(
            $this->tokenUrl,
            [
                'form_params' => [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'client_credentials',
                    'scope' => $this->scope,
                ],
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw new HTTPException(
                'Failed to fetch access token.',
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        $data = json_decode($response->getBody(), true);

        $this->accessToken = $data['access_token'];
        $this->expiresIn = $data['expires_in'];
        $this->tokenType = $data['token_type'];
        $this->lastFetchTime = time();
    }

    public function getAccessToken(): string
    {
        if (
            $this->accessToken === null ||
            (time() - $this->lastFetchTime) >= ($this->expiresIn - 60) // Refresh before it expires
        ) {
            $this->fetchToken();
        }

        return $this->accessToken;
    }

    public function getAuthorizationHeader(): array
    {
        return ['Authorization' => 'Bearer ' . $this->getAccessToken()];
    }
}