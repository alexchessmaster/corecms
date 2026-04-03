<?php

namespace App\Modules\Redirects\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Languages\Models\Language;
use App\Modules\Redirects\Models\Redirect;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\Redirects\Http\Requests\StoreRedirectRequest;
use App\Modules\Redirects\Http\Requests\UpdateRedirectRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RedirectController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Redirect::class);

        if ($request->ajax()) {
            $redirects = Redirect::query(); // Fetch redirects

            return DataTables::of($redirects)
                ->addColumn('action', function ($redirect) {
                    // Edit button
                    $editButton = '<a href="' . route('admin.redirects.edit', $redirect->id) . '" class="btn btn-primary btn-sm">Edit</a>';

                    // Delete button (using form submission)
                    $deleteButton = '
                <form action="' . route('admin.redirects.destroy', $redirect->id) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this redirect?\')">Delete</button>
                </form>
                ';

                    return $editButton . ' ' . $deleteButton; // Combine both buttons
                })
                ->make(true);
        }

        return view('redirects::redirect.index');
    }

    /**
     * Show the form for creating a new redirect.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', Redirect::class);

        $languages = Language::all();
        return view('redirects::redirect.create', compact('languages')); // Return the view to create a new redirect
    }

    /**
     * Store a newly created redirect in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', Redirect::class);

        $request->validate([
            'from' => 'required|string|max:255|unique:redirects,from',
            'to' => 'required|string|max:255',
            'language' => 'nullable|string|max:10',
            'type' => 'required|in:manual,import,slug_changed',
        ]);

        // Create the new redirect
        $redirect = new Redirect();
        $redirect->from = '/' . ltrim(rtrim($request->from, '/'), '/');
        $redirect->to = '/' . ltrim(rtrim($request->to, '/'), '/');
        $redirect->language = $request->language;
        $redirect->type = $request->type;
        $redirect->save();

        return redirect()->route('admin.redirects.index')->with('success', 'Redirect created successfully!');
    }

    /**
     * Display the specified redirect.
     *
     * @param  \App\Modules\Redirects\Models\Redirect  $redirect
     * @return \Illuminate\View\View
     */
    public function show(Redirect $redirect)
    {
        $this->authorize('view', $redirect);

        return view('redirects::redirect.show', compact('redirect')); // Return the show view for a single redirect
    }

    /**
     * Show the form for editing the specified redirect.
     *
     * @param  \App\Modules\Redirects\Models\Redirect  $redirect
     * @return \Illuminate\View\View
     */
    public function edit(Redirect $redirect)
    {
        $this->authorize('update', $redirect);

        $languages = Language::all();
        return view('redirects::redirect.edit', compact('redirect', 'languages')); // Return the view to edit a redirect
    }

    /**
     * Update the specified redirect in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modules\Redirects\Models\Redirect  $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Redirect $redirect)
    {
        $this->authorize('update', $redirect);

        $request->validate([
            'from' => 'required|string|max:255|unique:redirects,from,' . $redirect->id,
            'to' => 'required|string|max:255',
            'language' => 'nullable|string|max:10',
            'type' => 'required|in:manual,import,slug_changed',
        ]);

        // Update the redirect
        $redirect->from = '/' . ltrim(rtrim($request->from, '/'), '/');
        $redirect->to = '/' . ltrim(rtrim($request->to, '/'), '/');
        $redirect->language = $request->language;
        $redirect->type = $request->type;
        $redirect->save();

        return redirect()->route('admin.redirects.index')->with('success', 'Redirect updated successfully!');
    }

    /**
     * Remove the specified redirect from storage.
     *
     * @param  \App\Modules\Redirects\Models\Redirect  $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Redirect $redirect)
    {
        $this->authorize('delete', $redirect);

        $redirect->delete(); // Delete the redirect

        return redirect()->route('admin.redirects.index')->with('success', 'Redirect deleted successfully!');
    }
}
