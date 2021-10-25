<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MedUser;

use Illuminate\Support\Facades\Session;

class MeduserController extends Controller
{

    public function store(MedUser $meduser, Request $request)
    {

        if (Session::get('IS_LOGGED') == 1 )
        {
            $mediutente = $meduser->where('utente_id', Session::get('UTENTE_ID'));
            dd($mediutente);

            if ($mediutente->count() == 0) {
                $query = $meduser->create([
                    'email' => 'sample@email.com',
                    'is_logged'  => Session::get('IS_LOGGED'),
                    'nome'       => Session::get('NOME'),
                    'cognome'    => Session::get('COGNOME'),
                    'permessi'   => Session::get('PERMESSI'),
                    'utente_id'  => Session::get('UTENTE_ID'),
                    'specialita' => Session::get('SPECIALITA'),
                    'attivita'   => Session::get('ATTIVITA'),
                    'tipo'       => Session::get('TIPO')
                ]);
            }

            // $query = $meduser->create([

            //     'email' => 'sample@email.com',
            //     'is_logged'  => Session::get('IS_LOGGED'),
            //     'nome'       => Session::get('NOME'),
            //     'cognome'    => Session::get('COGNOME'),
            //     'permessi'   => Session::get('PERMESSI'),
            //     'utente_id'  => Session::get('UTENTE_ID'),
            //     'specialita' => Session::get('SPECIALITA'),
            //     'attivita'   => Session::get('ATTIVITA'),
            //     'tipo'       => Session::get('TIPO')

            // ]);
            $request->session()->flash('flash_message', 'Your user profile was created on \'Dialogposalute\' Please update your pasword under your profile settings.');
            return redirect('/home');
        }
        else{
            $request->session()->flash('flash_message', 'You have accessed this page in error.');
            return redirect('/home');
        }
    }
}
