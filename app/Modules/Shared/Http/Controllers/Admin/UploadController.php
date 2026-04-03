<?php

namespace App\Modules\Shared\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Shared\Models\Upload;
use App\Modules\Shared\Helpers\FileHelper;
use App\Modules\Shared\Http\Requests\StoreUploadRequest;
use App\Http\Requests\UpdateUploadRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UploadController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Upload::class);

        return view('shared::upload.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Upload::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUploadRequest $request)
    {
        $this->authorize('create', Upload::class);

        $path = FileHelper::upload($request);
        $parsedUrl = parse_url($path);
        $pathParts = explode('/', $parsedUrl['path']);
        $filename = array_pop($pathParts);
        $encodedFilename = rawurlencode($filename);
        $basePath = implode('/', $pathParts);
        $path = $basePath . '/' . $encodedFilename;

        return view('shared::upload.index', compact('path'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Upload $upload)
    {
        $this->authorize('view', $upload);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Upload $upload)
    {
        $this->authorize('view', $upload);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUploadRequest $request, Upload $upload)
    {
        $this->authorize('update', $upload);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Upload $upload)
    {
        $this->authorize('delete', $upload);
    }
}
