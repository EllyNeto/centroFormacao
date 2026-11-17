<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Course extends Model
{
    use SoftDeletes;
     
    protected array $fillable = [
        'name',
        'description',
        'duration',
        'status',
        'value'];
}
