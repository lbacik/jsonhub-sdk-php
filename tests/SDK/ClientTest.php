<?php

declare(strict_types=1);

namespace JsonHub\SDK\Tests;

use JsonHub\SDK\Client;
use JsonHub\SDK\Client\Mapper\DefinitionMapper;
use JsonHub\SDK\Client\Mapper\EntityCollectionMapper;
use JsonHub\SDK\Client\Mapper\EntityMapper;
use JsonHub\SDK\Client\MapperService;
use JsonHub\SDK\Client\RequestFactory;
use JsonHub\SDK\Client\UrlFactory;
use JsonHub\SDK\Definition;
use JsonHub\SDK\Entity;
use JsonHub\SDK\EntityCollection;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ClientTest extends TestCase
{
    private const API_URL = 'https://api.jsonhub.test';

    private Client $jsonHubClient;
    private ClientInterface $httpClient;

    public function setUp(): void
    {
        $this->httpClient = $this->createMock(ClientInterface::class);

        $this->jsonHubClient = new Client(
            $this->httpClient,
            new RequestFactory(
                new UrlFactory(self::API_URL)
            ),
            new MapperService([
                new EntityMapper(),
                new EntityCollectionMapper(
                    new EntityMapper(),
                ),
                new DefinitionMapper(),
            ]),
        );
    }

    public function testGetEntitiesByDefinition()
    {
        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn($this->createResponse([
                'entities' => [
                    [
                        'id' => '123',
                        'slug' => 'test',
                        'data' => [],
                        'definition' => '345',
                    ],
                ],
            ]));

        $result = $this->jsonHubClient->getEntitiesByDefinition('123');

        $this->assertInstanceOf(EntityCollection::class, $result);
        $this->assertCount(1, $result);
        $this->assertEquals('123', $result[0]->id);
    }

    public function testGetEntity()
    {
        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn($this->createResponse([
                    'id' => '123',
                    'slug' => 'test',
                    'data' => [],
                    'definition' => '345',
            ]));

        $result = $this->jsonHubClient->getEntity('123');

        $this->assertInstanceOf(Entity::class, $result);
        $this->assertEquals('123', $result->id);
    }

    public function testGetDefinition()
    {
        $this->httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn($this->createResponse([
                'id' => '123',
                'slug' => 'test',
                'jsonSchema' => [],
            ]));

        $result = $this->jsonHubClient->getDefinition('123');

        $this->assertInstanceOf(Definition::class, $result);
        $this->assertEquals('test', $result->slug);
    }

    private function createResponse(array $payload): ResponseInterface
    {
        $response = $this->createMock(ResponseInterface::class);
        $body = $this->createMock(StreamInterface::class);

        $body
            ->expects($this->once())
            ->method('getContents')
            ->willReturn(json_encode($payload));

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($body);

        return $response;
    }
}
