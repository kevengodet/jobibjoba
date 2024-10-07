<?php

declare(strict_types=1);

namespace Keven\JobiJoba;

use Http\Discovery\Psr18Client;
use Keven\JobiJoba\Exception\DomainException;
use Keven\JobiJoba\Exception\InvalidCredentials;
use Keven\JobiJoba\Exception\ServerException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

final class ApiClient
{
    private ?string $token = null;

    public function __construct(
        #[\SensitiveParameter]
        private string $clientId,
        #[\SensitiveParameter]
        private string $clientSecret,
        private string $country = 'fr',
        private ClientInterface $httpClient = new Psr18Client(),
    ) {}

    private function authenticateIfNeeded(): void
    {
        if ($this->token !== null) {
            return;
        }

        $request = $this->httpClient
            ->createRequest('POST', 'https://api.jobijoba.com/v3/'.$this->country.'/login')
            ->withBody(
                $this->httpClient->createStream(
                    json_encode([
                        'client_id'     => $this->clientId,
                        'client_secret' => $this->clientSecret,
                    ])
                )
            );

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw InvalidCredentials::fromPrevious($e);
        }

        if ($response->getStatusCode() >= 500) {
            throw new ServerException();
        }

        if ($response->getStatusCode() >= 400) {
            throw new InvalidCredentials();
        }

        $contents = json_decode($response->getBody()->getContents(), true);
        $this->token = $contents['token'];
    }

    public function search(string $what, string $where, int $page = null, int $pageSize = 20): Page
    {
        $this->authenticateIfNeeded();

        $args= [
            'what'  => $what,
            'where' => $where,
            'page'  => $page,
            'limit' => $pageSize,
        ];
        $args = http_build_query(array_filter($args, fn($a) => !is_null($a)));

        $request = $this->httpClient
            ->createRequest('GET', 'https://api.jobijoba.com/v3/'.$this->country.'/ads/search?'.$args)
            ->withHeader('Authorization', 'Bearer '.$this->token);

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw InvalidCredentials::fromPrevious($e);
        }

        if ($response->getStatusCode() >= 500) {
            throw new ServerException();
        }

        // Token has expired
        if ($response->getStatusCode() === 401) {
            unset($this->token);

            return $this->search(...func_get_args());
        }

        if ($response->getStatusCode() >= 400) {

            throw new DomainException();
        }

        $contents = json_decode($response->getBody()->getContents(), true);

        return new Page(
            Jobs::fromArray($contents['data']['ads']),
            $contents['data']['total'],
            $page ?? 1,
            $pageSize
        );
    }
}
