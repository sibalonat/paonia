<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function privacy()
    {

      return view('frontend.footers.privacy') ;
    }
    public function medikey()
    {

      return view('frontend.footers.credits') ;
    }
    public function legali()
    {

      return view('frontend.footers.note_legali') ;
    }
}
