<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Table;

class MenuController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $tables = Table::all();
        // ...tambahkan data lain jika perlu
        return view('user.orders.menu', compact('products', 'tables'));
    }
}
