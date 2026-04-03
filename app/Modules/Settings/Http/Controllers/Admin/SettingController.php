<?php

namespace App\Modules\Settings\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Models\Setting;
use App\Modules\Settings\Repositories\SettingRepository;
use App\Modules\Settings\Http\Requests\StoreSettingRequest;
use App\Modules\Settings\Http\Requests\UpdateSettingRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SettingController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private SettingRepository $settingRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Setting::class);

        $settings = $this->settingRepository->all();

        return view('settings::setting.index', compact('settings'));
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
    public function store(StoreSettingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($settingId)
    {
        $setting = $this->settingRepository->findById($settingId);
        $this->authorize('view', $setting);

        $values = [];
        if (!empty($setting->is_translatable)) {
            $values = unserialize($setting->value);
        }

        return view('settings::setting.edit', compact('setting', 'values'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSettingRequest $request, int $settingId)
    {
        $setting = $this->settingRepository->findById($settingId);
        $this->authorize('update', $setting);
        if ($request->has('is_translatable')) {
            $values = $request->all();
            unset($values['_method']);
            unset($values['_token']);
            unset($values['is_translatable']);
            unset($values['value']);
            unset($values['description']);
            $setting->value = serialize($values);
            $setting->is_translatable = true;
        } else {
            $setting->value = $request->input('value');
            $setting->is_translatable = false;
        }
        $setting->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        $this->authorize('delete', $setting);
    }
}
