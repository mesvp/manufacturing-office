<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Envelope;
use Mail;

class DemoController extends Controller
{
    
    public function demo()
    {
    //     $data = array('name'=>"Virat Gandhi");
    //   Mail::send(['text'=>'Mail'], $data, function($message) {
    //      $message->to('ravibakoriya@gmail.com', 'Tutorials Point')->subject
    //         ('Laravel HTML Testing Mail');
    //      $message->from('info@jobssolution.in','Virat Gandhi');
    //   });
    Mail::raw('dsffsfs', function ($m)  {
       $m->from('info@jobssolution.in', 'Your Application');

        $m->to('ravibakoriya@gmail.com', 'dfsdffs')->subject('Your Reminder!');
    });
     
    }
}