<?php

namespace App\Services\Admin;

use App\Traits\DynamoLocalTrait;
use Aws\DynamoDb\Marshaler;
use Illuminate\Http\Response;
use DateTimeImmutable;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Builder;


class LoginService
{
  use DynamoLocalTrait;

  private $loginService;
  private $marshaler;

  public function __construct(Marshaler $marshaler)
  {
    $this->marshaler = $marshaler;
  }


  public function getAdminUserFromDynamoDB($adminUserId)
  {
    $result = $this->getDynamoLocalClient()->getItem([
      'TableName' => 'test_registger',
      'Key' => [
        'id' => ['S' => $adminUserId],
      ],
      'ProjectionExpression' => 'id,email,password',
    ]);
    return $this->marshaler->unmarshalItem($result['Item']);
    // 取得したユーザー情報を返す
    return $result['Item'] ?? null;
  }


  public function setJwtTokenCookie()
  {
    $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
    $algorithm    = new Sha256();
    $signingKey   = InMemory::plainText(env('JWT_SECRET'));

    $now   = new DateTimeImmutable();
    $token = $tokenBuilder
      // JWT発行者(サーバー側)の識別子
      ->issuedBy('http://localhost:5000')
      // JWT を利用する主体(クライアント側)の識別子
      ->permittedFor('http://localhost:4000')
      // JWTの主語となる主体の識別子
      // ->relatedTo('subject comment')
      // JWTのための一意(ユニーク)な識別子。重複が起きないように割り当てる必要がある。
      // ->identifiedBy('4f1g23a12aa')
      // JWTの発行日時。
      ->issuedAt($now)
      // 有効期間開始日時。これ以前にこのJWTの処理はNG。
      ->canOnlyBeUsedAfter($now)
      // 有効期限日時。期限以降にこのJWTの処理はNG
      ->expiresAt($now->modify('+1 hour'))
      // カスタムクレーム(パラメータ)
      ->withClaim('uid', 1)
      ->withClaim('uid2', 2)
      // Configures a new header, called "foo"
      ->withHeader('foo', 'bar')
      // Builds a new token
      ->getToken($algorithm, $signingKey);

    // レスポンスを作成
    $response = new Response(['message' => 'Authentication successful']);

    // トークンをCookieに追加
    $response->cookie('token',  $token->toString(), $now->modify('+1 hour')->getTimestamp());

    // レスポンスを返す
    return $response;
  }
}
