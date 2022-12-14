<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opinion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'opinion',
        'reason',
        'topic_id'
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
