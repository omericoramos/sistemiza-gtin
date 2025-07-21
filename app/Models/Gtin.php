<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gtin extends Model
{
    protected $table = 'gtins';

    protected $fillable = [
        'gtin_code',
        'description',
        'cest',
        'cst_pis',
        'cst_cofins',
        'cst_icms',
    ];
}
