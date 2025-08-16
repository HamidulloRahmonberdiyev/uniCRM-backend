<?php

namespace App\Helpers\Pagination;

class PaginationHelper
{
  public static function format($paginator): array
  {
    return [
      'current_page' => $paginator->currentPage(),
      'last_page'    => $paginator->lastPage(),
      'per_page'     => $paginator->perPage(),
      'total_items'  => $paginator->total(),
    ];
  }
}
