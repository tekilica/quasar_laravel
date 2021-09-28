<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    protected $table = 'user_role';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    protected $fillable = [
        'role_id'
    ];
}
