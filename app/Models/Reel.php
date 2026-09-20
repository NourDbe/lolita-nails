<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Reel extends Model { protected $fillable=['title','video','poster','label','is_published','sort_order']; protected function casts(): array { return ['is_published'=>'boolean']; } }
