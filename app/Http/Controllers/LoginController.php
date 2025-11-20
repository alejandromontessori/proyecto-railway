<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function check_login(Request $request){
        $credenciales=$request->validate([
            "name"=>"required",
            "password"=>["required", "min:6"]

        ]);
        if ($credenciales["password"]=="pepeperez"){
            return redirect("recetas");

        }else{
            var_dump($credenciales);
            return view("welcome");

        }

    }
}
