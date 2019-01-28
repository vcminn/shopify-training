<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BundleProduct extends Model
{
    protected $fillable = [
        'store_id', 'bundle_id', 'product_id', 'quantity', 'stock',
    ];

    public function bundle()
    {
        return $this->belongsTo('App\Bundle');
    }
}
