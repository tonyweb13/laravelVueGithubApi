<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\GitHubService;

class TaskController extends Controller
{

    protected GitHubService $githubService;

    public function __construct(GitHubService $githubService)
    {
        $this->githubService = $githubService;
    }

    public function getIssues()
    {
        $issues = $this->githubService->getAssignedIssues();

        return response()->json($issues);
    }

    public function getIssue($issueNumber)
    {
        $response = $this->githubService->getIssue($issueNumber);

        return response()->json($response);
    }

    public function closeIssue($issueNumber)
    {
        $response = $this->githubService->closeIssue($issueNumber);

        return response()->json($response);
    }
}
