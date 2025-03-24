<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class GitHubService
{
    protected $token;
    protected $repo;
    protected string $githubUsername;

    public function __construct()
    {
        $this->token = Config::get('services.github.token');
        $this->repo = Config::get('services.github.repo');
        $this->githubUsername = config('services.github.username');
    }

    public function getAssignedIssues()
    {
        $url = "https://api.github.com/repos/{$this->repo}/issues";

        $response = Http::withHeaders([
            'Authorization' => "token {$this->token}",
            'Accept' => 'application/vnd.github.v3+json',
        ])->get($url, [
            'state' => 'open',
            'assignee' => $this->githubUsername,
        ]);

        if ($response->failed()) {
            return ['error' => $response->json(), 'status' => $response->status()];
        }

        return $response->json();
    }

    public function getIssue($issueNumber)
    {
        $url = "https://api.github.com/repos/{$this->repo}/issues/{$issueNumber}";

        $response = Http::withHeaders([
            'Authorization' => "token {$this->token}",
            'Accept' => 'application/vnd.github.v3+json',
        ])->get($url);

        if ($response->failed()) {
            return response()->json([
                'error' => $response->json(),
                'status' => $response->status()
            ], $response->status());
        }

        return $response->json();
    }

    public function closeIssue($issueNumber)
    {
        $url = "https://api.github.com/repos/{$this->repo}/issues/{$issueNumber}";

        $response = Http::withHeaders([
            'Authorization' => "token {$this->token}",
            'Accept' => 'application/vnd.github.v3+json',
        ])->patch($url, [
            'state' => 'closed',
        ]);

        Log::info('GitHub API Response:', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        if ($response->failed()) {
            return [
                'error' => $response->json(),
                'status' => $response->status(),
            ];
        }

        return [
            'message' => "Issue #{$issueNumber} has been closed successfully!",
            'issue' => $response->json(),
        ];
    }


}
