<?php

namespace App\Support;

use App\Models\User;
use DateTimeImmutable;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
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
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Jwt
{
    private Configuration $configuration;

    public function __construct()
    {
        $this->configuration = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText(config('jwt.secret_key'))
        );

        $this->configuration->setValidationConstraints(
            new SignedWith(
                new Sha256(),
                InMemory::plainText(config('jwt.secret_key'))
            ),
            new StrictValidAt(SystemClock::fromUTC())
        );
    }

    public function generate(User $user): string
    {
        $now = new DateTimeImmutable();

        return $this->configuration->builder()
            ->issuedBy(config('app.url'))
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify(config('jwt.expire')))
            ->withClaim('uid', $user->getAuthIdentifier())
            ->withClaim('email', $user->email)
            ->getToken($this->configuration->signer(), $this->configuration->signingKey())
            ->toString();
    }

    public function parse(string $token): UnencryptedToken
    {
        $token = $this->configuration->parser()->parse($token);

        $constraints = $this->configuration->validationConstraints();

        if (! $this->configuration->validator()->validate($token, ...$constraints)) {
            throw new BadRequestHttpException('Invalid token !');
        }

        assert($token instanceof UnencryptedToken);

        return $token;
    }
}
