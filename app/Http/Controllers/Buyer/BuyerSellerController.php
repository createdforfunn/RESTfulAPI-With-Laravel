<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {

        // EAGER Loading.

        /**  NOTE:
         *  'transaction' is relationship in Buyer model,
         *  'product' is a relationship in Transaction model,
         *  'seller' is a relationship in Product model
        */
        $sellers = $buyer->transactions()->with('product.seller')
            ->get()
            ->pluck('product.seller')     // In response: there is a 'seller' property in each 'product' property for each collection item.
            ->unique('id')                 // If there is repeatition then the repeated objects will be empty and not removed completely ??
            ->values();                        // Leave the empty objects ?

        return $this->showAll($sellers);
    }
}
