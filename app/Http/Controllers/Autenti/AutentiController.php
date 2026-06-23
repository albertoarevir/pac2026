<?php

namespace App\Http\Controllers\Autenti;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AutentiController extends Controller
{
    public function index()
    {
        return view('Autenti.registerUser');
    }

    public function store(Request $request)
    {
        // pendiente de implementación
        return redirect()->back()->with('info', 'Funcionalidad en desarrollo.');
    }
}
