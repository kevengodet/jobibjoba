<?php

declare(strict_types=1);

namespace Keven\JobiJoba;

final readonly class Job
{
    public function __construct(
        public string $id,
        public string $link,
        public string $title,
        public string $description,
        public \DateTimeInterface $publicationDate,
        public string $coordinates,
        public string $city,
        public string $postalCode,
        public string $department,
        public string $region,
        public string $sector,
        public string $jobTitle,
        public string $company,
        public array $contractType,
        public string $salary
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['link'],
            $data['title'],
            $data['description'],
            new \DateTimeImmutable($data['publicationDate']),
            $data['coordinates'],
            $data['city'],
            $data['postalCode'],
            $data['department'],
            $data['region'],
            $data['sector'],
            $data['jobtitle'],
            $data['company'],
            $data['contractType'],
            $data['salary'],
        );
    }
}
