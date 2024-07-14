<?php

namespace App\Services;

use Cache;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Symfony\Component\Uid\Uuid;


class JwtService
{
    private Configuration $config;

    public function __construct()
    {
        $this->config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::base64Encoded(config('app.jwt_secret'))
        );
    }

    public function createToken(): string
    {
        $now = new \DateTimeImmutable();
        $uuid = Uuid::v7();

        $token = $this->config->builder()
            ->issuedAt($now)
            ->withHeader('jti', $uuid->toRfc4122())
            ->expiresAt($now->modify('+40 minutes'))
            ->getToken($this->config->signer(), $this->config->signingKey());

        ray($token);

        return $token->toString();
    }

    public function validateToken(string $rawToken): bool
    {
        try {
            $token = $this->config->parser()->parse($rawToken);

            $jti = $token->headers()->get('jti');

            if (!$jti) {
                return false;
            }

            $constraints = [
                new SignedWith($this->config->signer(), $this->config->signingKey()),
            ];

            $this->config->validator()->assert($token, ...$constraints);

            // Will be better use Redis
            // But think it`s enough for this test task
            if (Cache::has("used_token:$jti")) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function setTokenToUsed(string $rawToken): void
    {
        try {
            $token = $this->config->parser()->parse($rawToken);
            $jti = $token->headers()->get('jti');

            Cache::put("used_token:$jti", true, now()->addMinutes(40));
        } catch (\Exception $e) {
            // Do nothing
        }
    }
}