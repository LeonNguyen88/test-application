<?php

namespace App\Repositories;

use App\Contracts\ElasticSearchRepositoryContract;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ElasticSearchRepository implements ElasticSearchRepositoryContract
{

    private Client $client;
    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([config('test.elasticsearch_host')])
            ->build();
    }

    /**
     * @param Collection $modelData
     * @param string $index
     * @param array $searchable
     * @return void
     */
    public function index(Collection $modelData, string $index, array $searchable) {
        $modelData->each(function ($model) use ($index, $searchable) {
                $this->client->index([
                    'id' => $model->id,
                    'index' => $index,
                    'body' => $this->convertSearchableColumn($searchable, $model)
                ]);
        });
    }

    /**
     * @param string $index
     * @return void
     */
    public function flush(string $index) {
        $this->client->indices()->delete(['index' => $index]);
    }

    /**
     * @param string $query
     * @param string $index
     * @param array $searchable
     * @return array
     */
    public function multipleMatch(string $query, string $index, array $searchable): array
    {
        $response = $this->client->search([
            'index' => $index,
            'size' => 10000,
            'scroll' => '1s',
            'body'  => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => $searchable
                    ]
                ]
            ]
        ]);
        $bookIds = [];

        while (isset($response['hits']['hits']) && count($response['hits']['hits']) > 0) {

            $bookIds = array_merge($bookIds, array_column($response['hits']['hits'], '_id'));
            $scrollId = $response['_scroll_id'];

            // Execute a Scroll request and repeat
            $response = $this->client->scroll([
                'body' => [
                    'scroll_id' => $scrollId,
                    'scroll'    => '1s'
                ]
            ]);
        }
        return array_filter($bookIds);
    }

    /**
     * @param array $searchable
     * @param Model $model
     * @return array
     */
    public function convertSearchableColumn(array $searchable, Model $model): array
    {
        return array_reduce($searchable, function ($searchableArray, $column) use ($model) {

            $searchableArray[$column] = $model[$column];
            return $searchableArray;
        }, []);
    }
}
