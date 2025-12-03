<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LanguageController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $this->authorize('viewAny', Language::class);

        if ($request->ajax()) {
            $data = Language::all();
            return datatables()
                ->of($data)
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.languages.edit', $row->id);
                    $deleteUrl = route('admin.languages.destroy', $row->id);
                    return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-primary">Edit</a>
                    <form action="' . $deleteUrl . '" method="POST" style="display: inline-block;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                    </form>
                ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.language.index');
    }

    public function create()
    {
        $this->authorize('create', Language::class);
        
        return view('admin.language.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Language::class);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:2',
            'default' => 'nullable|boolean',
            'use_separate_domain' => 'nullable|boolean',
            'domain' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
        ]);

        $language = new Language;
        $language->name = $request->input('name');
        $language->code = $request->input('code');
        $language->default = $request->boolean('default');
        $language->use_separate_domain = $request->boolean('use_separate_domain');
        $language->domain = $request->input('domain');
        $language->image = $request->input('image');
        $language->save();

        return redirect()->route('admin.languages.index')->with('success', 'Language created successfully.');
    }

    public function edit(Language $language)
    {
        $this->authorize('view', $language);
        
        return view('admin.language.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $this->authorize('update', $language);
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:2',
            'default' => 'nullable|boolean',
            'use_separate_domain' => 'nullable|boolean',
            'domain' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
        ]);
        $language->name = $request->input('name');
        $language->code = $request->input('code');
        $language->default = $request->boolean('default');
        $language->use_separate_domain = $request->boolean('use_separate_domain');
        $language->domain = $request->input('domain');
        $language->image = $request->input('image');
        $language->save();

        return redirect()->route('admin.languages.index')->with('success', 'Language updated successfully.');
    }

    public function destroy(Language $language)
    {
        $this->authorize('delete', $language);
        
        $language->delete();
        return redirect()->route('admin.languages.index')->with('success', 'Language deleted successfully.');
    }

    public function setUserLocale()
    {
        session(['lang' => request()->lang]);
        App::setLocale(request()->lang);

        return redirect()->back();
    }
}
