<?php

declare(strict_types=1);

namespace App\Types;

class ObjectTableData
{
    /** @var array<int, mixed> */
    public array $data;

    /** @var array<string, string> */
    public array $columns;

    public int $page;

    public int $perPage;

    public int $totalRecords;

    public int $totalPages;

    public bool $hasMore;

    /**
     * @param array{
     *     data: array<int, mixed>,
     *     columns: array<string, string>,
     *     page: int,
     *     perPage: int,
     *     totalRecords: int,
     *     totalPages: int,
     *     hasMore: bool,
     * } $attributes
     */
    public function __construct(array $attributes)
    {
        $this->data = $attributes['data'];
        $this->columns = $attributes['columns'];
        $this->page = $attributes['page'];
        $this->perPage = $attributes['perPage'];
        $this->totalRecords = $attributes['totalRecords'];
        $this->totalPages = $attributes['totalPages'];
        $this->hasMore = $attributes['hasMore'];
    }
}
