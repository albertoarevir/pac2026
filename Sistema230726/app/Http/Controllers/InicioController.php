<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       // dd($request->all());

        if (isset($request->rut_funcionario)) {
            session([
                'run' => $request->rut_funcionario,
                'tokenAcceso' => $request->accessToken,
                'sitio' => $request->website,
                'token' => $request->_token,
            ]);


          return redirect()->route('admin.index');

        } else {
           return view('inicio');
        }



        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function login()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
