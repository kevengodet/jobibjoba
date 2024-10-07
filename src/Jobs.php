<?php

declare(strict_types=1);

namespace Keven\JobiJoba;

final class Jobs implements \Countable, \IteratorAggregate
{
    /** @var Job[] */
    private array $jobs;

    public function __construct(Job ...$jobs)
    {
        $this->jobs = $jobs;
    }

    public static function fromArray(array $jobs): self
    {
        return new self(...array_map(fn($a) => Job::fromArray($a), $jobs));
    }

    /** @return Job[] */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->jobs);
    }

    public function count(): int
    {
        return count($this->jobs);
    }
}
