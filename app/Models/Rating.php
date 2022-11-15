<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

//Extiende de pivot porque Rating es una tabla intermedia
class Rating extends Pivot
{
    use HasFactory;

    /*
    *Indica que los campos de la tabla son autoincrementables 
    */
    public $incrementing = true;

    protected $table = 'ratings';

    public function rateable()
    {
        return $this->morphTo();
    }

    public function qualifier()
    {
        return $this->morphTo();
    }
}
