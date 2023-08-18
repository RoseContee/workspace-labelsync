<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'value',
    ];

    /**
     * @param array|string|null $keys
     * @param string|null $default
     * @return array|string|null
     */
    public static function getSetting($keys = null, $default = null) {
        $setting = [];
        switch (gettype($keys)) {
            case 'string':
                $item = self::where('key', $keys)->first();
                $setting = $item['value'] ?? $default;
                break;
            case 'array':
                $settings = self::whereIn('key', $keys)->pluck('value', 'key');
                foreach ($keys as $key) {
                    $setting[$key] = $settings[$key] ?? ($default[$key] ?? null);
                }
                break;
            default :
                $setting = self::pluck('value', 'key')->toArray();
        }
        return $setting;
    }

    /**
     * @param array|string
     * @param string|null
     */
    public static function saveSetting($key, $value = null) {
        if (is_array($key)) {
            $settings = [];
            foreach ($key as $k => $v) {
                $settings[] = [
                    'key'   => $k,
                    'value' => $v
                ];
            }
            self::upsert($settings, ['key'], ['value']);
        } else if (gettype($key) == 'string') {
            self::updateOrCreate([
                'key'   => $key,
            ], [
                'value' => $value,
            ]);
        }
    }
}
