<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BundleProduct extends Model
{
    protected $fillable = [
        'store_id', 'bundle_id', 'variant_id', 'quantity', 'stock', 'image','price'
    ];

    public function bundle()
    {
        return $this->belongsTo('App\Bundle');
    }
}
