<?php

namespace App\Stores;

use App\Modules\Shared\Enums\SettingKeyEnum;
use App\Models\Setting;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class SettingStore
{
    private Collection $allSettings;

    public function __construct()
    {
        $this->allSettings = Setting::all();
    }

    public function all(): Collection
    {
        return $this->allSettings;
    }

    public function findById(int $id)
    {
        $setting = $this->allSettings->firstWhere('id', $id);

        if (!$setting) {
            throw new ModelNotFoundException('The settings model not found for id: ' . $id);
        }

        return $setting;
    }

    public function findByKey(SettingKeyEnum $key)
    {
        $setting = $this->allSettings->firstWhere('key', $key->value);
        if (!$setting) {
            throw new ModelNotFoundException('The settings model not found for key: ' . $key->value);
        }
        if ($setting->is_translatable) {
            $value = unserialize($setting->value)[app()->getLocale()];
        } else {
            $value = $setting->value;
        }

        return $value;
    }

    public function isTranslatable(SettingKeyEnum $key): bool
    {
        $setting = $this->allSettings->firstWhere('key', $key->value);
        if (!$setting) {
            throw new ModelNotFoundException('The settings model not found for key: ' . $key->value);
        }

        return $setting->is_translatable ? true : false;
    }
    
    public function updateByKey(SettingKeyEnum $key, string $value): Setting
    {
        // not used anywhere, but take care of is_translatable later
        $setting = Setting::where('key', $key->value)->firstOrFail();
        $setting->value = $value;
        $setting->save();

        $this->allSettings = $this->allSettings
            ->reject(fn($item) => $item->key === $key->value)
            ->push($setting);

        return $setting;
    }
}
