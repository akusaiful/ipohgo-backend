<?php
namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    // use SoftDeletes;
    protected $table = 'user_device';
    protected $fillable = [
        'fcm_token',
        'user_id',        
    ];
    
}
