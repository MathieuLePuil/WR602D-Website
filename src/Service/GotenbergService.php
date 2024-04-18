<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GotenbergService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function generatePdfFromUrl(string $url): ?string
    {
        try {
            $response = $this->client->request(
                'POST',
                'https://gotenberg.mathieulp.fr/api/convert',
                [
                    'json' => ['url' => $url]
                ]
            );

            // Assuming the response will always be successful and contain the 'pdf_url'
            $data = $response->toArray();

            return $data['pdf_url'] ?? null;
        } catch (\Exception $e) {
            // Log the exception or handle it as per your requirement
            return null;
        }
    }

}

