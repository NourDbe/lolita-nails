<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Review extends Model { protected $fillable=['client_name','body','rating','is_published','sort_order']; protected function casts(): array { return ['is_published'=>'boolean']; } }
