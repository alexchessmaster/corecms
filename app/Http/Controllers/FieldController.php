<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FieldController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $this->authorize('viewAny', Field::class);

        if ($request->ajax()) {
            $data = Field::select(['id', 'type']);
            return datatables()
                ->of($data)
                ->editColumn('type', function ($item) {
                    return $item->type;
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.fields.edit', $row->id);
                    $deleteUrl = route('admin.fields.destroy', $row->id);
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

        return view('admin.field.index');
    }

    public function create()
    {
        $this->authorize('create', Field::class);

        return view('admin.field.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Field::class);
        $request->validate([
            'type' => 'required|string|max:255',
        ]);

        $field = new Field;
        $field->type = $request->input('type');
        $field->save();

        return redirect()->route('admin.fields.index')->with('success', 'Field created successfully.');
    }

    public function edit(Field $field)
    {
        // $this->authorize('update', $field);

        // return view('admin.field.edit', compact('field'));
    }

    public function update(Request $request, Field $field)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        // ]);

        // $field->name = $request->input('name');
        // $field->save();

        // return redirect()->route('admin.fields.index')->with('success', 'Field updated successfully.');
    }

    public function destroy(Field $field)
    {
        // $field->delete();
        // return redirect()->route('admin.fields.index')->with('success', 'Field deleted successfully.');
    }
}
