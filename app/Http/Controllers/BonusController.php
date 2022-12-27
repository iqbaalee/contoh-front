<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BonusController extends Controller
{
    public function index()
    {
        return view('pages.bonus.index', ['title' => 'Bonus']);
    }
}
