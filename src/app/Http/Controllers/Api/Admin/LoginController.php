<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\LoginService;
use App\Traits\DynamoLocalTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// jwtlib
use DateTimeZone;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;

class LoginController extends Controller
{
    use DynamoLocalTrait;

    private $loginService;
    private $config;

    public function __construct(LoginService $loginService)
    {
        $this->config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText(env('JWT_SECRET')));
        $this->loginService = $loginService;
    }

    public function test(Request $request)
    {
       return 'ok';
    }


    public function login(Request $request)
    {
        $adminUser = $this->loginService->getAdminUserFromDynamoDB($request->id);
        // ユーザーが存在し、パスワードが一致すれば認証成功
        if ($adminUser && Hash::check($request->password, $adminUser['password'])) {
            // cookieにjwttokenをセットする
            return $this->loginService->setJwtTokenCookie();
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
