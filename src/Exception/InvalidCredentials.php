<?php

declare(strict_types=1);

namespace Keven\JobiJoba\Exception;

final class InvalidCredentials extends DomainException implements JobiJobaException
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct("Invalid credentials", $code, $previous);
    }
}
