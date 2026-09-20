<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Service extends Model {
 protected $fillable=['name_ar','name_en','description_ar','description_en','price','duration_minutes','image','is_active','sort_order'];
 protected function casts(): array { return ['price'=>'decimal:0','is_active'=>'boolean']; }
 public function appointments(): HasMany { return $this->hasMany(Appointment::class); }
}
