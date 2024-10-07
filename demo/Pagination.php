<?php

declare(strict_types=1);

use Keven\JobiJoba\Page;

final class Pagination
{
    public const ELLIPSIS = 37716515;

    public function __construct(private Page $page) {}

    public function generate(int $radius = 3): string
    {
        if (0 === $this->page->totalCount) {
            return '';
        }

        $html = '<nav>';
        $uri = Uri::current();
        foreach ($this->listPages($radius) as $pageNumber) {
            if ($pageNumber === self::ELLIPSIS) {
                $html .= ' &#8230; ';
            } elseif ($pageNumber === $this->page->currentPage) {
                $html .= ' '.$pageNumber.' ';
            } else {
                $html .= ' <a href="'.$uri->withQueryParam('page', $pageNumber).'">'.$pageNumber.'</a> ';
            }
        }
        $html .= '</nav>';

        return $html;
    }

    /** @return int[] */
    private function listPages(int $radius = 3): array
    {
        $pages = [];

        if (0 === $this->page->totalCount) {
            return $pages;
        }

        $nbPages = ceil($this->page->totalCount / $this->page->pageSize);

        // Always link to first page
        $pages[] = 1;

        // Limits of pages around current one
        $lower = max(2, $this->page->currentPage - $radius);
        $upper = min($nbPages - 1, $this->page->currentPage + $radius);

        if ($lower > 2) {
            $pages[] = self::ELLIPSIS;
        }

        for ($i = $lower ; $i <= $upper ; $i++) {
            $pages[] = $i;
        }

        if ($upper < $nbPages - 1) {
            $pages[] = self::ELLIPSIS;
        }

        // Link to last page if not already linked
        if ($nbPages > 2) {
            $pages[] = $nbPages;
        }

        return $pages;
    }
}
