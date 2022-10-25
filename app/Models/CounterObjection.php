<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterObjection extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'body',
        'topic_id'
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
