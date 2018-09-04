<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use SoftDeletes;

    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';


    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ];


    protected $hidden = [
        'pivot'
    ];


    public function isAvailable()
    {
        return $this->status == Product::AVAILABLE_PRODUCT;
    }


    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }



    // Handling the model event by event observer approach.  // learn event queueing
    public static function boot()
    {
        parent::boot();

        // Register an updating model event with the dispatcher.
        parent::updating(function ($product) {

            if ($product->quantity == 0 && $product->isAvailable()) {
                $product->status = self::UNAVAILABLE_PRODUCT;
            }
        });
    }
}
