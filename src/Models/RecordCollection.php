<?php

namespace Webleit\ZohoBooksApi\Models;

use Illuminate\Support\Collection;
use Webleit\ZohoBooksApi\Request\Pagination;

/**
 * Page Context
 * @package Webleit\ZohoBooksApi\Modules
 */
class RecordCollection extends Collection
{
    /**
     * @var null|Pagination
     */
    protected $pagination;

    public function withPagination(Pagination $pagination): self
    {
        $this->pagination = $pagination;

        return $this;
    }

    public function pagination(): Pagination
    {
        return $this->pagination;
    }
}
