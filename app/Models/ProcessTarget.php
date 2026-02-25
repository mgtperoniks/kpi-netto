<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasDepartmentScope;

class ProcessTarget extends Model
{
    use HasDepartmentScope;

    protected $fillable = [
        'department_code',
        'process_name',
        'month',
        'year',
        'target_qty',
    ];
}
