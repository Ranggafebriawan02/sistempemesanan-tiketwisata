<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $paket = Paket::latest()->take(6)->get();

        return view('home', compact('paket'));
    }

    public function paket(): View
    {
        $paket = Paket::latest()->get();

        return view('paket.index', compact('paket'));
    }

    public function show(Paket $paket): View
    {
        return view('paket.show', compact('paket'));
    }
}
