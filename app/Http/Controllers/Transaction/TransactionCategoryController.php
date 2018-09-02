<?php

namespace App\Http\Controllers\Transaction;

use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class TransactionCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Transaction $transaction)
    {

        /**
         *  NO need of EAGER Loading here.
         *  $transacion->product will NOT retrun a collection because there is 1 to 1 relation between them.
         *  and there is a 'categories' relation in Product model which can be used to get all the categories to
         *  which the product belong
         */
        $categories = $transaction->product->categories;
        //$categories = $transaction->product->categories->pluck('name');

        return $this->showAll($categories);
    }
}
