<?php

declare(strict_types=1);

namespace Keven\JobiJoba;

final readonly class Page
{

    public function __construct(
        public Jobs $jobs,
        public int  $totalCount,
        public int  $currentPage,
        public int  $pageSize,
    ) {}
}
