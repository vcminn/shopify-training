<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    protected $fillable = [
        'store_id',
        'bundle_style',
        'image_style',
        'widget_title',
        'internal_name',
        'description',
        'image',
        'discount',
        'active',
        'base_total_price',
        'test'
    ];

    public function bundle_products()
    {
        return $this->hasMany('App\BundleProduct');
    }
}
