<?php

namespace App\Modules\Forms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Forms\Http\Requests\StoreFormContactUsRequest;
use App\Modules\Forms\Http\Requests\UpdateFormContactUsRequest;
use App\Modules\Forms\Models\FormContactUs;

class FormContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormContactUsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FormContactUs $formContactUs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FormContactUs $formContactUs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormContactUsRequest $request, FormContactUs $formContactUs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormContactUs $formContactUs)
    {
        //
    }
}
