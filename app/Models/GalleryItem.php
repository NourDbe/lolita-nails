<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class GalleryItem extends Model { protected $fillable=['title','image','category','is_featured','sort_order']; protected function casts(): array { return ['is_featured'=>'boolean']; } }
