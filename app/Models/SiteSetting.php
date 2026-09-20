<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SiteSetting extends Model {
 protected $fillable=['key','value'];
 public static function valueOf(string $key, mixed $default=null): mixed { return static::where('key',$key)->value('value') ?? $default; }
 public static function put(string $key, mixed $value): void { static::updateOrCreate(['key'=>$key],['value'=>$value]); }
 public static function allKeyed(): array { return static::query()->pluck('value','key')->all(); }
}
