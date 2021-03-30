<?php


namespace Webleit\ZohoBooksApi\Request;

class Pagination
{
    protected $perPage = 200;
    protected $page = 1;
    protected $moreRecords = false;
    protected $total = null;
    protected $totalPages = null;

    public function __construct(array $params = [])
    {
        $this->perPage = $params['per_page'] ?? $this->perPage;
        $this->page = $params['page'] ?? $this->page;
        $this->moreRecords = $params['has_more_page'] ?? $this->moreRecords;
        $this->total  = $params['total'] ?? null;
        $this->totalPages  = $params['total_pages'] ?? null;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function page(): int
    {
        return $this->page;
    }

    public function hasMoreRecords(): bool
    {
        return $this->moreRecords;
    }

    /**
     * Get the value of total
     *
     * @return int|null
     */
    public function total(): ?int
    {
        return $this->total;
    }

    /**
     * Get the value of totalPages
     *
     * @return int|null
     */
    public function totalPages(): ?int
    {
        return $this->totalPages;
    }
}
