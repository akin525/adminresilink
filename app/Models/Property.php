<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'properties';
    protected $guarded=[];

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
