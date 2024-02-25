<?php

namespace App\Models;

use BaoPham\DynamoDb\DynamoDbModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminUser extends DynamoDbModel
{
    use HasFactory;

    protected $table = 'test_registger';

}
