<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class MyOperatorService
{
    protected string $baseUrl;
    protected string $companyId;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('myoperator.base_url');
        $this->companyId = config('myoperator.company_id');
        $this->apiKey = config('myoperator.api_key');
    }

    private function client()
    {
        return Http::timeout(60)
            ->retry(1, 2000)
            ->withoutVerifying()
            ->withHeaders([
                'X-MYOP-COMPANY-ID' => $this->companyId,
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);
    }

    /* FETCH CALLS */
    public function getCalls(int $limit = 10, int $offset = 0, array $filters = [])
    {
        $params = array_merge([
            'limit' => min($limit, 100),
            'offset' => $offset,
        ], $filters);

        try {
            return $this
                ->client()
                ->get($this->baseUrl . '/search', $params) // The MyOperator API for fetching call logs is usually /search
                ->json();
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }

    private function error($e)
    {
        return [
            'status' => 'error',
            'message' => 'MyOperator API connection failed',
            'error' => $e->getMessage(),
        ];
    }
}
