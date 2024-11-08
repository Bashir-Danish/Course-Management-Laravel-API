<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Pagination
{
    protected function getPaginationParams(Request $request)
    {
        return [
            'page' => $request->input('page', 1),
            'limit' => $request->input('limit', 10)
        ];
    }
} 