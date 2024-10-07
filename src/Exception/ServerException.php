<?php

declare(strict_types=1);

namespace Keven\JobiJoba\Exception;

class ServerException extends \Exception implements JobiJobaException
{
    public static function fromPrevious(\Throwable $e): self
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }
}
