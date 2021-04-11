<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Status;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        $statuses = Status::all();

        return view('home', compact('tasks', 'statuses'));
    }
}
