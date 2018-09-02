<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        // $products = $buyer->transactions->products;  Error: No product property on collection


        // EAGER LOADING

        /** NOTE: transaction() will return an instance of a model, and not a collection,
         *  to get collection finally use get() method.
         */
        $products = $buyer->transactions()->with('product')   // here 'product' is a relation in Transaction model
            ->get()
            ->pluck('product');    // after get() we get a collection.  pluck() is method on collection

        return $this->showAll($products);
    }
}
