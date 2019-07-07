<?php


namespace App\Http\Controllers;


class IndexController extends Controller
{
    public function index(){
        echo "hello world";
    }

    public function laravel(){
        return view('welcome');
    }

    public function test(){
        echo "test 111";
    }
}
