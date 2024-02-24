<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Aws\Sdk;
use Aws\DynamoDb\DynamoDbClient;

class TestController extends Controller
{
    public function test()
    {

        $client = new DynamoDbClient([
            'region'      => 'ap-northeast-1',
            'version'     => 'latest',
            'endpoint' => 'http://host.docker.internal:4566',
            'credentials' => [
                'key'    => 'test',
                'secret' => 'test',
            ],
        ]);


        // テーブル一覧を取得
        $result = $client->listTables();

        // 取得したテーブル一覧を出力
        $tables = $result->get('TableNames');
        foreach ($tables as $tableName) {
            echo $tableName . "\n";
        }
    }
}
