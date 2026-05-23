<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait ApiRequestsTrait
{
  protected function request(
    string $method,
    string $url,
    ?string $token = null,
    array $data = [],
    int $timeout = 10
  ): \Illuminate\Http\Client\Response {
    $http = Http::timeout($timeout);

    if ($token) $http = $http->withToken($token);

    return $http->$method($url, $data);
  }
}
