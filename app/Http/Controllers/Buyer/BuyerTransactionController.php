<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {

        /**
         * $transactions=  $buyer->transactions()->get();  // transactions() return a Query or Builder subclass,
         * so use get() to get the collection
         */
        $transactions = $buyer->transactions;  // return a collection


        return $this->showAll($transactions);
    }
}
