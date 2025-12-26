<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MdMachine extends Model
{
    protected $table = 'md_machines';

    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'line',
        'active',
    ];
    public function scopeActive($query)
{
    return $query->where('active', 1);
}

}
