<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'position',
        'status',
        'category_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function objections()
    {
        return $this->hasMany(Objection::class);
    }

    public function counterObjections()
    {
        return $this->hasMany(CounterObjection::class);
    }

    public function opinions()
    {
        return $this->hasMany(Opinion::class);
    }

    public static function reverseStatus($status)
    {
        return $status == '完了' ? '0' : '1';
    }
}
