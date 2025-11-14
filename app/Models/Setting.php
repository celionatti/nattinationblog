<?php

declare(strict_types=1);

namespace App\Models;

/*
|--------------------------------------------------------------------------
| Setting Model
|--------------------------------------------------------------------------
| This model represents the application settings.
| It includes attributes and methods for managing site-wide configurations.
*/

use PDO;
use Plugs\Base\Model\PlugModel;

/**
 * Setting Model
 * 
 * @package App\Models
 */

class Setting extends PlugModel
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $fillable = ['setting_key', 'setting_value', 'setting_type', 'setting_group'];
    // protected $casts = [
    //     'setting_value' => 'array',
    // ];

    // Setting groups
    const GROUP_GENERAL = 'general';
    const GROUP_WRITING = 'writing';
    const GROUP_READING = 'reading';
    const GROUP_DISCUSSION = 'discussion';
    const GROUP_MEDIA = 'media';
    const GROUP_PERMALINKS = 'permalinks';
    const GROUP_ADVANCED = 'advanced';

    /**
     * Get all settings with proper error handling
     */
    public static function getAllSettings(): array
    {
        try {
            $settings = static::all();

            if ($settings->isEmpty()) {
                return [];
            }

            $result = [];
            foreach ($settings as $setting) {
                $result[$setting->setting_key] = $setting->setting_value;
            }

            return $result;
        } catch (\Exception $e) {
            error_log("GetAllSettings error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get settings by group - FIXED
     */
    public static function getByGroup(string $group): array
    {
        try {
            // Use instanceWhere instead of where for proper query building
            $settings = static::query()->instanceWhere('setting_group', $group)->get();

            if ($settings->isEmpty()) {
                return [];
            }

            $result = [];
            foreach ($settings as $setting) {
                $result[$setting->setting_key] = $setting->setting_value;
            }

            return $result;
        } catch (\Exception $e) {
            error_log("GetByGroup error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a specific setting value - FIXED
     */
    public static function getValue(string $key, $default = null)
    {
        try {
            $setting = static::query()->instanceWhere('setting_key', $key)->first();
            return $setting ? $setting->setting_value : $default;
        } catch (\Exception $e) {
            error_log("GetValue error for key {$key}: " . $e->getMessage());
            return $default;
        }
    }

    /**
     * Set a setting value - FIXED
     */
    public static function setValue(string $key, $value, string $group = self::GROUP_GENERAL): bool
    {
        try {
            $setting = static::query()->instanceWhere('setting_key', $key)->first();

            if (!$setting) {
                $setting = new static();
                $setting->setting_key = $key;
                $setting->setting_group = $group;
                $setting->setting_type = self::determineType($value);
            }

            $setting->setting_value = $value;
            return $setting->save();
        } catch (\Exception $e) {
            error_log("SetValue error for key {$key}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Determine type based on value
     */
    private static function determineType($value): string
    {
        if (is_bool($value))
            return 'boolean';
        if (is_int($value))
            return 'integer';
        if (is_array($value) || is_object($value))
            return 'json';
        return 'string';
    }

    /**
     * Update multiple settings at once
     */
    public static function updateMultiple(array $settings): bool
    {
        try {
            foreach ($settings as $key => $value) {
                $group = self::determineGroup($key);
                static::setValue($key, $value, $group);
            }
            return true;
        } catch (\Exception $e) {
            error_log("UpdateMultiple error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Determine setting group based on key
     */
    private static function determineGroup(string $key): string
    {
        $groupMap = [
            'site_' => self::GROUP_GENERAL,
            'default_' => self::GROUP_WRITING,
            'homepage_' => self::GROUP_READING,
            'allow_' => self::GROUP_DISCUSSION,
            'comment_' => self::GROUP_DISCUSSION,
            'mail_' => self::GROUP_WRITING,
            'feed_' => self::GROUP_READING,
            'search_engine_' => self::GROUP_READING,
        ];

        foreach ($groupMap as $prefix => $group) {
            if (str_starts_with($key, $prefix)) {
                return $group;
            }
        }

        return self::GROUP_GENERAL;
    }

    /**
     * Get default settings structure
     */
    public static function getDefaultSettings(): array
    {
        return [
            // General Settings
            'site_title' => 'BlogName',
            'site_tagline' => 'Just another WordPress site',
            'site_url' => 'https://blogname.com',
            'admin_email' => 'admin@blogname.com',
            'timezone' => 'UTC',
            'membership' => true,

            // Writing Settings
            'default_category' => 1,
            'default_post_format' => 'standard',
            'mail_server' => '',
            'mail_port' => 110,
            'mail_login' => '',
            'mail_password' => '',

            // Reading Settings
            'homepage_display' => 'latest',
            'posts_per_page' => 10,
            'feed_show' => 'full_text',
            'search_engine_visibility' => false,

            // Discussion Settings
            'allow_pings' => true,
            'allow_comments' => true,
            'comment_registration' => true,
            'comment_moderation' => false,
            'comment_nesting' => true,
            'comment_levels' => 5,
            'comments_per_page' => 50,
        ];
    }

    /**
     * Initialize default settings if they don't exist
     */
    public static function initializeDefaults(): void
    {
        try {
            $defaults = self::getDefaultSettings();

            foreach ($defaults as $key => $value) {
                $exists = static::query()->instanceWhere('setting_key', $key)->exists();
                if (!$exists) {
                    static::setValue($key, $value);
                }
            }
        } catch (\Exception $e) {
            error_log("InitializeDefaults error: " . $e->getMessage());
        }
    }

    /**
     * Override getAttribute to handle value casting
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if ($key === 'setting_value') {
            // Handle different value types based on setting_type
            $type = $this->attributes['setting_type'] ?? 'string';

            switch ($type) {
                case 'boolean':
                    return (bool) $value;
                case 'integer':
                    return (int) $value;
                case 'json':
                    if (is_string($value)) {
                        $decoded = json_decode($value, true);
                        return $decoded !== null ? $decoded : $value;
                    }
                    return $value;
                case 'string':
                default:
                    return $value;
            }
        }

        return $value;
    }

    /**
     * Override setAttribute to handle value casting
     */
    public function setAttribute($key, $value)
    {
        if ($key === 'setting_value') {
            // Determine and set the type
            $type = $this->determineType($value);
            $this->attributes['setting_type'] = $type;

            // Convert value based on type
            switch ($type) {
                case 'json':
                    if (is_array($value) || is_object($value)) {
                        $value = json_encode($value);
                    }
                    break;
                case 'boolean':
                    $value = $value ? 1 : 0;
                    break;
            }
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Debug method to check what's happening
     */
    public static function debugSettings(): array
    {
        try {
            $settings = static::all();
            $result = [
                'count' => $settings->count(),
                'data' => []
            ];

            foreach ($settings as $setting) {
                $result['data'][] = [
                    'id' => $setting->id,
                    'key' => $setting->setting_key,
                    'value' => $setting->setting_value,
                    'type' => $setting->setting_type,
                    'group' => $setting->setting_group
                ];
            }

            return $result;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}