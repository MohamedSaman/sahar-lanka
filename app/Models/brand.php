<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class brand extends Model
{
    use HasFactory;
    protected $fillable = ['brand_name', 'notes'];

    public function products()
    {
        return $this->hasMany(ProductDetail::class, 'brand_id');
    }
}
