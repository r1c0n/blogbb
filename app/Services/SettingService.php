<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Collection;

class SettingService
{
    /**
     * Get all settings as a collection
     */
    public function all(): Collection
    {
        return Setting::all()->mapWithKeys(function ($setting) {
            return [$setting->key => $setting->getCastValue()];
        });
    }

    /**
     * Get a setting value
     */
    public function get(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }

    /**
     * Set a setting value
     */
    public function set(string $key, $value, string $type = 'string'): Setting
    {
        return Setting::set($key, $value, $type);
    }

    /**
     * Set multiple settings at once
     */
    public function setMultiple(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $type = $this->detectType($value);
            $this->set($key, $value, $type);
        }
    }

    /**
     * Check if site is in maintenance mode
     */
    public function isMaintenanceMode(): bool
    {
        return (bool) $this->get('maintenance', false);
    }

    /**
     * Enable maintenance mode
     */
    public function enableMaintenance(): void
    {
        $this->set('maintenance', true, 'boolean');
    }

    /**
     * Disable maintenance mode
     */
    public function disableMaintenance(): void
    {
        $this->set('maintenance', false, 'boolean');
    }

    /**
     * Get site configuration
     */
    public function getSiteConfig(): array
    {
        return [
            'name' => $this->get('site_name', 'BlogBB'),
            'description' => $this->get('site_description', 'A modern blog platform'),
            'url' => $this->get('site_url', config('app.url')),
            'image' => $this->get('site_image', ''),
            'announcement' => $this->get('site_announcement', ''),
            'maintenance' => $this->isMaintenanceMode(),
        ];
    }

    /**
     * Update site configuration
     */
    public function updateSiteConfig(array $config): void
    {
        if (isset($config['name'])) {
            $this->set('site_name', $config['name']);
        }
        if (isset($config['description'])) {
            $this->set('site_description', $config['description']);
        }
        if (isset($config['url'])) {
            $this->set('site_url', $config['url']);
        }
        if (isset($config['image'])) {
            $this->set('site_image', $config['image']);
        }
        if (isset($config['announcement'])) {
            $this->set('site_announcement', $config['announcement']);
        }
    }

    /**
     * Clear settings cache
     */
    public function clearCache(): void
    {
        Setting::clearCache();
    }

    /**
     * Detect the type of a value
     */
    protected function detectType($value): string
    {
        return match (true) {
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            is_float($value) => 'float',
            is_array($value) => 'array',
            default => 'string',
        };
    }
}
