<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $sellers = $buyer->transactions()->with('product.categories')
            ->get()                               // return collection of transaction, each transaction with the product and the product with the collection of Categories.
            ->pluck('product.categories')   // return a collection with many categories collection (means a collection of collections)
            ->collapse()                          // take all the items from all the collections and put them in a single collection.
            ->unique('id')
            ->values();

        return $this->showAll($sellers);
    }
}
