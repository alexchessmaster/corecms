<?php

namespace App\Modules\Books\Http\Controllers\Api\V1;

use App\Modules\Books\Models\BookAuthor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookAuthorResource;

class BookAuthorController extends Controller
{
    public function index(Request $request)
    {
        if (!empty(request()->lang)) {
            app()->setLocale(request()->lang);
        }

        $query = BookAuthor::query();

        // Search functionality for Select2
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereRaw("LOWER(JSON_EXTRACT(name, '$." . app()->getLocale() . "')) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("LOWER(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%{$search}%"]);
            });
        }

        $authors = $query->paginate(50);

        return BookAuthorResource::collection($authors);
    }
}
