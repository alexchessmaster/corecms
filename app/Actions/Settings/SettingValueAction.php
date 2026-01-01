<?php

namespace App\Actions\Settings;

use App\Models\Setting;
use Illuminate\Support\Arr;

final class SettingValueAction
{
    /**
     * Cache all settings for this request.
     *
     * @var array<string, Setting>
     */
    private static array $allSettings = [];

    /**
     * Get the value of a setting for a given locale.
     */
    public function get(string $key, ?string $locale = null): ?string
    {
        $setting = $this->getSetting($key);

        if (! $setting->is_translatable) {
            return $setting->value;
        }

        $locale ??= app()->getLocale();

        $values = $this->deserialize($setting->value);

        return Arr::get($values, $locale, null);
    }

    /**
     * Update the value of a setting for a given locale.
     */
    public function update(string $key, string $value, ?string $locale = null): void
    {
        $setting = $this->getSetting($key);

        if (! $setting->is_translatable) {
            $setting->value = $value;
        } else {
            $locale ??= app()->getLocale();
            $values = $this->deserialize($setting->value);
            $values[$locale] = $value;
            $setting->value = $this->serialize($values);
        }

        $setting->save();

        // Update memory cache
        self::$allSettings[$key] = $setting;
    }

    /**
     * Load all settings into memory once per request.
     */
    private function getSetting(string $key): Setting
    {
        if (empty(self::$allSettings)) {
            self::$allSettings = Setting::all()->keyBy('key')->toArray();
        }

        if (! isset(self::$allSettings[$key])) {
            throw new \RuntimeException("Setting '$key' not found");
        }

        return (object) self::$allSettings[$key]; // return as object for property access
    }

    private function serialize(array $values): string
    {
        return serialize($values);
    }

    private function deserialize(?string $value): array
    {
        return $value ? unserialize($value, ['allowed_classes' => false]) : [];
    }
}