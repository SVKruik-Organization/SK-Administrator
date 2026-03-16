<?php

declare(strict_types=1);

namespace App\Types;

class OrderedTableData
{
    /** @var array<int, mixed> */
    public array $data;

    /** @var array<string, string> */
    public array $columns;

    public int $totalRecords;

    /**
     * @param array{
     *     data: array<int, mixed>,
     *     columns: array<string, string>,
     *     totalRecords: int,
     * } $attributes
     */
    public function __construct(array $attributes)
    {
        $this->data = $attributes['data'];
        $this->columns = $attributes['columns'];
        $this->totalRecords = $attributes['totalRecords'];
    }
}
