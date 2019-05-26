<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
	// retorna a view responsável por listar o rankink dos players
  public function index() {
  	return view('index');
  }
}
