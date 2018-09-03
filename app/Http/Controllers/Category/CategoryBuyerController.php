<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $buyers = $category->products()
            ->whereHas('transactions')
            ->with('transactions.buyer')
            ->get()                          // { data(ie products):[M(product){...,transactions:[M(transaction){...,buyer}]}] }
            ->pluck('transactions')    // { data(ie transactions):[M(transactions)[M(transaction){...,buyer}]] }
            ->collapse()                     // { data(ie transactions):[M(transaction){...,buyer}] }
            ->pluck('buyer')           // { data(ie buyers): [M(buyer){...,buyer}] }
            ->unique('id')
            ->values();

        return $this->showAll($buyers);
    }
}



