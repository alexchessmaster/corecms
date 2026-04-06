<?php

namespace App\Modules\Comments\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Comments\Models\Commentable;
use Illuminate\Http\Request;
use App\Modules\Comments\Http\Requests\StoreCommentableRequest;
use App\Modules\Comments\Http\Requests\UpdateCommentableRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentableController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Commentable::class);

        if ($request->ajax()) {
            $commentables = Commentable::select(['id', 'commentable_id', 'commentable_type', 'content', 'user_id', 'name', 'email', 'stars', 'total_votes', 'created_at', 'status']);

            return datatables()->of($commentables)
                ->editColumn('content', function ($commentable) {
                    $content = $commentable->getTranslation('content', app()->getLocale(), false);
                    return $content ?: '-Not translated-' . $commentable->getTranslation('content', app()->getLocale(), true);
                })
                ->addColumn('actions', function ($commentable) {
                    return '
                        <a href="' . route('admin.comments.edit', $commentable) . '" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="' . route('admin.comments.destroy', $commentable) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('comments::comment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Commentable::class);

        return view('comments::comment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentableRequest $request)
    {
        $this->authorize('create', Commentable::class);
        $data = $request->validated();
        $comment = new Commentable;
        $comment->fill($data);
        $comment->setTranslation('content', app()->getLocale(), $data['content']);
        $comment->save();

        return redirect()->route('admin.comments.edit', $comment->id)
            ->with('success', 'Comment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Commentable $comment)
    {
        // return view('admin.comment.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commentable $comment)
    {
        $this->authorize('update', $comment);

        return view('comments::comment.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentableRequest $request, Commentable $comment)
    {
        $this->authorize('update', $comment);
        $data = $request->validated();
        $comment->fill($data);
        $comment->setTranslation('content', app()->getLocale(), $data['content']);
        $comment->stars = !empty($data['stars']) ? $data['stars'] : 0;
        $comment->save();

        return redirect()->back()->with('success', 'Comment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commentable $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return redirect()->route('admin.comments.index')->with('success', 'Comment deleted successfully.');
    }
}
