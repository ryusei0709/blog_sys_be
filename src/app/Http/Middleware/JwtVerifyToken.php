<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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




class JwtVerifyToken
{
    
    private $config;

    public function __construct()
    {
        $this->config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText(env('JWT_SECRET')));
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $jwt = $request->header('token');

        $token = $this->config->parser()->parse($jwt);

        $timeZone = new DateTimeZone('Asia/Tokyo');

        $clock = new SystemClock($timeZone);

        $constraints = [
            // トークンが予期された署名者とキーで署名されたかどうかを検証します
            new SignedWith($this->config->signer(), $this->config->verificationKey()),
            // 発行元の確認
            new IssuedBy('http://localhost:5000'),
            // tokenの有効期限のvalidation
            new Constraint\StrictValidAt($clock)
        ];

        try {
            $this->config->validator()->assert($token, ...$constraints);
        } catch (RequiredConstraintsViolated $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        // Tokenの検証に成功した場合は次の処理（コントローラーなど）に進む
        return $next($request);
    }
}
