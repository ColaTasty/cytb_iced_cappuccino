<?php


namespace App\Http\Controllers;


class LaravelHomeController extends Controller
{
    public function Index(){
        return view('welcome');
    }
}
