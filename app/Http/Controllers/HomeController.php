<?php

namespace App\Http\Controllers;

use App\Categorie;
use App\Client;
use App\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function select()
    {
        return view('select',[

            // 'clients' => Client::all()

        ]);
    }

    // public function selectProduit()
    // {
    //     return view('managements.selection.select',[

    //         // 'clients' => Client::all()

    //     ]);
    // }

   

    public function application()
    {    
        // dd(Auth::user()->email);
        return view('application');
    }
    
}
