<?php

namespace App\Http\Controllers\Autenti;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;



class AutentiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Session::put('  ', 'Esto es el valor de la variable de sesion');

        return view('Autenti.registerUser');
    }
    
}