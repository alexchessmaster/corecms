<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Models\Upload;
use App\Http\Requests\StoreUploadRequest;
use App\Http\Requests\UpdateUploadRequest;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.upload.index');
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
    public function store(StoreUploadRequest $request)
    {
        $path = FileHelper::upload($request);
        $parsedUrl = parse_url($path);
        $pathParts = explode('/', $parsedUrl['path']);
        $filename = array_pop($pathParts);
        $encodedFilename = rawurlencode($filename);
        $basePath = implode('/', $pathParts);
        $path = $basePath . '/' . $encodedFilename;

        return view('admin.upload.index', compact('path'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Upload $upload)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Upload $upload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUploadRequest $request, Upload $upload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Upload $upload)
    {
        //
    }
}
