<?php

namespace Kizi\Shopify\Services;

use App\Exceptions\SoheadException;
use GraphQL\Client;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Kizi\Shopify\Contracts\ShopifyContract;

class ShopifyService implements ShopifyContract
{
    private $domain;
    private $endPoint;
    private $version;
    private $token;
    private $partnerId;

    public function __construct()
    {
        $this->version = config('shopify.version');
    }

    public function installUrl()
    {
        $clientId    = config('shopify.client_id');
        $scopes      = implode(
            ',',
            config('shopify.scopes')
        );
        $redirectUri = config('shopify.redirect_url');
        return $this->domain."/admin/oauth/authorize?client_id={$clientId}&scope={$scopes}&redirect_uri={$redirectUri}";
    }

    public function appUrl()
    {
        $clientId    = config('shopify.client_id');
        return $this->domain."/admin/apps/{$clientId}";
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setDomain($domain)
    {
        if ((!(substr($domain, 0, 7) == 'http://')) && (!(substr($domain, 0, 8) == 'https://'))) {
            $domain = 'https://'.$domain;
        }
        $domain = str_replace(
            '.myshopify.com',
            '',
            $domain
        );
        $this->domain = $domain.'.myshopify.com';
        return $this;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
        return $this;
    }

    public function getEndPoint()
    {
        return $this->endPoint;
    }

    public function setPartnerId($partnerId)
    {
        $this->partnerId = $partnerId;
        return $this;
    }

    public function getPartnerId()
    {
        return $this->partnerId;
    }

    public function runQl($graphQuery, $variables = [], $resultsAsArray = true)
    {
        $client = new Client(
            $this->domain.'/'.$this->endPoint,
            [
                'Accept'                 => 'application/json',
                'Content-Type'           => 'application/json',
                'X-Shopify-Access-Token' => $this->token,
            ]
        );

        $result = $client->runRawQuery($graphQuery, $resultsAsArray, $variables);
        return $result->getData();
    }

    public function runApi($method, array $variables = [])
    {
        $url = $this->domain.'/admin/api/'.$this->version.'/'.$this->endPoint;
        $http = Http::withHeaders([
            'Content-Type'           => 'application/json',
            'X-Shopify-Access-Token' => $this->token
        ]);
        switch (strtolower($method)) {
            case 'get':
                $response = $http->get($url, $variables);
                $result = [
                    'status' => $response->successful(),
                    'data'   => $response->collect()->toArray(),
                ];
                break;
            case 'post':
                $response = $http->post($url, $variables);
                $result = [
                    'status' => $response->successful(),
                    'data'   => $response->collect()->toArray(),
                ];
                break;
            case 'put':
                $response = $http->put($url, $variables);
                $result = [
                    'status' => $response->successful(),
                    'data'   => $response->collect()->toArray(),
                ];
                break;
            case 'delete':
                $response = $http->delete($url, $variables);
                $result = [
                    'status' => $response->successful(),
                    'data'   => $response->collect()->toArray(),
                ];
                break;
            default:
                $result = [
                    'status' => false,
                    'code'   => 500
                ];
                break;
        }
        return $result;
    }

    public function auth(array $variables=[]): bool
    {
        $tmp = [];
        if (is_string($variables)) {
            $each = explode(
                '&',
                $variables
            );
            foreach ($each as $e) {
                list($key, $val) = explode(
                    '=',
                    $e
                );
                $tmp[$key] = $val;
            }
        } elseif (is_array($variables)) {
            $tmp = $variables;
        } else {
            return false;
        }

        // Timestamp check; 1 hour tolerance
        if (($tmp['timestamp'] - time() > 3600)) {
            return false;
        }

        if (array_key_exists(
            'hmac',
            $tmp
        )) {
            // HMAC Validation
            $queryString = http_build_query(
                [
                    'code'      => $tmp['code'],
                    'shop'      => $tmp['shop'],
                    'timestamp' => $tmp['timestamp'],
                ]
            );
            $match       = $tmp['hmac'];
            $calculated  = hash_hmac(
                'sha256',
                $queryString,
                config('shopify.client_secret')
            );

            return $calculated === $match;
        }

        return false;
    }
}
