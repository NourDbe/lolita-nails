<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Appointment extends Model {
 protected $fillable=['service_id','client_name','phone','appointment_date','appointment_time','notes','status'];
 protected function casts(): array { return ['appointment_date'=>'date']; }
 public function service(): BelongsTo { return $this->belongsTo(Service::class); }
}
