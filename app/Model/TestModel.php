<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    public $primaryKey="id";
    protected $table = 'belief';
    protected $guarded = [];
    public $timestamps = false;    
}
