<?php

namespace App\Support;

use App\Models\User;
use DateTimeImmutable;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Jwt
{
    private readonly Builder $tokenBuilder;

    private readonly Signer $algorithm;

    private readonly Key $signingKey;

    public function __construct()
    {
        $this->tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $this->algorithm = new Sha256();
        $this->signingKey = InMemory::plainText(config('jwt.secret_key'));
    }

    public function generate(User $user): string
    {
        $now = new DateTimeImmutable();

        return $this->tokenBuilder
            ->issuedBy(config('app.url'))
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify(config('jwt.expire')))
            ->withClaim('uid', $user->getAuthIdentifier())
            ->withClaim('email', $user->email)
            ->getToken($this->algorithm, $this->signingKey)
            ->toString();
    }

    public function parse(string $token): UnencryptedToken
    {
        $parser = new Parser(new JoseEncoder());

        try {
            $token = $parser->parse($token);
        } catch (CannotDecodeContent|InvalidTokenStructure|UnsupportedHeaderFound $e) {
            throw new BadRequestHttpException('Invalid token !');
        }

        assert($token instanceof UnencryptedToken);

        return $token;
    }
}
