<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::patch('/github/issues/{issueNumber}', [TaskController::class, 'closeIssue']);
