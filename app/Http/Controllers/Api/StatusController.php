<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Status;
use Illuminate\Support\Facades\DB;
class StatusController extends Controller
{
    //
    public function index(){
        //return Status::all();
       
       return $res = DB::select('select title as label
       from statuses 
       order by id'); 
       
            
    }
}
