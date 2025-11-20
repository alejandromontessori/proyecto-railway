<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class InicioController extends Controller
{
    public function index()
    {


        return view('index', [


            //'nombreUsuario' => Session::get('nombreUsuario'),
            //'administrador' => Session::get('administrador', 0)
        ]);
    }
}
