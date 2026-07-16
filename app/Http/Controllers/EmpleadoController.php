<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Database\Eloquent\Relations\HasMany;
class EmpleadoController extends Controller
{
    //
   public function inicio()
    {
   
        return view('empleado/inicio' );
    }


}
