<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Traits\DynamoLocalTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class AdminContrller extends Controller
{

    use DynamoLocalTrait;

    public function register(Request $request)
    {
        $result = $this->getDynamoLocalClient()->putItem([
            'TableName' => 'test_registger',
            'Item' => [
                'id' => ['S' => $request->input('id')],
                'name' => ['S' => $request->input('name')],
                'email' => ['S' => $request->input('email')],
                'password' => ['S' => Hash::make($request->input('password'))],
            ],
        ]);

        return $result;
    }
}
