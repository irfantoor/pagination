<?php

namespace Tests;

use IrfanTOOR\Pagination;

class MockPagination extends Pagination
{
    protected $current_page = 1;

    public function getVar($v)
    {
        return $this->$v;
    }

    public function setCurrentPage(int $current_page)
    {
        $this->current_page = $current_page;
    }

    public function currentPage(): int
    {
        return $this->current_page;
    }
}
