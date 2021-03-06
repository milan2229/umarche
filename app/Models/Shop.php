<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;
use App\Models\Product;

class Shop extends Model
{
    use HasFactory;
    // protected $table = 'shops';

    protected $fillable = [
        'owner_id',
        'name',
        'information',
        'filename',
        'is_selling'
    ];

    //hasManyはたくさんあるよ
    //belomgsToは属しているよ
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
