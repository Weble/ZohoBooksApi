<?php

namespace Webleit\ZohoBooksApi\Models;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Page Context
 * @package Webleit\ZohoBooksApi\Modules
 */
class PageContext implements \JsonSerializable, Arrayable
{
    protected int $page;
    protected int $perPage;
    protected ?int $total = null;
    protected ?int $totalPages = null;
    protected bool $hasMorePage = false;
    protected string $sortColumn = '';
    protected string $sortOrder = 'D';

    public function __construct(array $context = [])
    {
        $this->page = $context['page'];
        $this->perPage = $context['per_page'];
        $this->total  = $context['total'] ?? null;
        $this->totalPages  = $context['total_pages'] ?? null;
        $this->hasMorePage = $context['has_more_page'];
        $this->sortColumn = $context['sort_column'];
        $this->sortOrder = $context['sort_order'];
    }

    /**
     * Get the value of page
     *
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * Get the value of perPage
     *
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Get the value of total
     *
     * @return int|null
     */
    public function getTotal(): ?int
    {
        return $this->total;
    }

    /**
     * Get the value of totalPages
     *
     * @return int|null
     */
    public function getTotalPages(): ?int
    {
        return $this->totalPages;
    }

    /**
     * Get the value of hasMorePage
     *
     * @return bool
     */
    public function getHasMorePage(): bool
    {
        return $this->hasMorePage;
    }

    /**
     * Get the value of sortColumn
     *
     * @return string
     */
    public function getSortColumn(): string
    {
        return $this->sortColumn;
    }

    /**
     * Get the value of sortOrder
     *
     * @return string
     */
    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'page' => $this->getPage(),
            'perPage' => $this->getPerPage(),
            'total' => $this->getTotal(),
            'totalPages' => $this->getTotalPages(),
            'hasMorePage' => $this->getHasMorePage(),
            'sortColumn' => $this->getSortColumn(),
            'sortOrder' => $this->getSortOrder()
        ];
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
