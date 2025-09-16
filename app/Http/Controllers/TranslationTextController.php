<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TranslationText;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreTranslationTextRequest;
use App\Http\Requests\UpdateTranslationTextRequest;
use App\Models\Language;

class TranslationTextController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TranslationText::class, 'translation_text');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TranslationText::select(['id', 'key', 'text']);
            return datatables()
                ->of($data)
                ->editColumn('text', function ($article) {
                    $text = $article->getTranslation('text', app()->getLocale(), false);
                    return $text ?: '--Not translated-- ' . $article->getTranslation('text', app()->getLocale(), true);
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.translation-texts.edit', $row->id);
                    $deleteUrl = route('admin.translation-texts.destroy', $row->id);
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

        return view('admin.translation-text.index');
    }

    /**
     * Show the form for creating a new translation.
     */
    public function create()
    {
        $languages = Language::all();

        return view('admin.translation-text.create', compact('languages'));
    }

    /**
     * Store a newly created translation in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $translations = [];
        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'lang-')) {
                $languageCode = str_replace('lang-', '', $key);
                $translations[$languageCode] = $value;
            }
        }

        $translationText = new TranslationText;
        $translationText->key = $request->input('key');
        $translationText->setTranslations('text', $translations);
        $translationText->save();

        return redirect()->route('admin.translation-texts.index')->with('success', 'Translation created successfully.');
    }

    /**
     * Display the specified translation.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified translation.
     */
    public function edit(TranslationText $translationText)
    {
        $languages = Language::all();
        $translations = $translationText->getTranslations('text');

        return view('admin.translation-text.edit', compact('translationText', 'languages', 'translations'));
    }

    /**
     * Update the specified translation in storage.
     */
    public function update(Request $request, TranslationText $translationText)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $translations = [];
        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'lang-')) {
                $languageCode = str_replace('lang-', '', $key);
                $translations[$languageCode] = $value;
            }
        }

        $translationText->key = $request->input('key');
        $translationText->setTranslations('text', $translations);
        $translationText->save();

        return redirect()->route('admin.translation-texts.index')->with('success', 'Translation updated successfully.');
    }

    /**
     * Remove the specified translation from storage.
     */
    public function destroy($id)
    {
        $translation = TranslationText::findOrFail($id);
        $translation->delete();

        return redirect()->route('admin.translation-texts.index')
            ->with('success', 'Translation deleted successfully.');
    }
}
