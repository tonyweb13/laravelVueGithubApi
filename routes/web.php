<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/github/issues', [TaskController::class, 'getIssues']);
Route::get('/api/github/issues/{issueNumber}', [TaskController::class, 'getIssue']);
