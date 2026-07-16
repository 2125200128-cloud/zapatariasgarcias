<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClienteController extends Controller
{
    //
    public function inicio()
    {
        return view('cliente/inicio');
    }
    
}
