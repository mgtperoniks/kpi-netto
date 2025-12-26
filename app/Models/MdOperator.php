<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MdOperator extends Model
{
    protected $table = 'md_operators';

    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'active',
    ];
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
