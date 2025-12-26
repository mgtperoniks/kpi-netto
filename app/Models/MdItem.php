<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MdItem extends Model
{
    protected $table = 'md_items';

    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'cycle_time_sec',
        'active',
    ];
    public function scopeActive($query)
{
    return $query->where('active', 1);
}

}
