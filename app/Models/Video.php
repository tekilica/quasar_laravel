<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory, SoftDeletes;

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function lastEditedBy()
    {
        return $this->belongsTo(User::class, 'last_edited_by');
    }

    protected $table = 'video';

    protected $fillable = [
        'name',
        'description',
        'author',
        'agency',
        'platform',
        'is_nsfw',
        'uploaded_by',
        'last_edited_by',
        'file',
    ];
}
