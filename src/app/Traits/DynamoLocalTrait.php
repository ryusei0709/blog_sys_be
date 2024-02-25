<?php

namespace App\Traits;

use Aws\DynamoDb\DynamoDbClient;

trait DynamoLocalTrait {

    private $client;

    public function getDynamoLocalClient()
    {
        $this->client = new DynamoDbClient([
            'region'      => 'ap-northeast-1',
            'version'     => 'latest',
            'endpoint' => 'http://host.docker.internal:4566',
            'credentials' => [
                'key'    => 'test',
                'secret' => 'test',
            ],
        ]);

        return $this->client;
    }

}
