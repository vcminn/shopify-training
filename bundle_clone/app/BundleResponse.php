<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BundleResponse extends Model
{
    protected $fillable = [
        'store_id', 'bundle_id', 'sales', 'visitors', 'added_to_cart',
    ];

    public function bundle()
    {
        return $this->belongsTo('App\Bundle');
    }
}
