<?php/** Abdukodir Khojiyev* Backend Developer* Project: encoderuz/uzpost* Date:  11/03/25*/namespace Encoderuz\Uzpost\Http;use Encoderuz\Uzpost\Exceptions\UzpostException;use Encoderuz\Uzpost\Services\CustomerService;use GuzzleHttp\Client;use GuzzleHttp\Exception\GuzzleException;class UzpostClient{    protected string $baseUrl;    protected string $apiKey;    protected Client $httpClient;    private array $logs = [];    public array $result = [];    public function __construct($apiKey = "", string $baseUrl = 'https://prodapi.pochta.uz/')    {        $this->httpClient = new Client([            'base_uri' => $baseUrl        ]);        $this->baseUrl = rtrim($baseUrl, '/');;        $this->apiKey = $apiKey;    }    /**     * @throws UzpostException     */    public static function token(string $username, string $password): UzpostClient    {        $client = new Client([            'base_uri' => "https://prodapi.pochta.uz/api/v1",            "headers"  => [                "Accept"       => "application/json",                "Content-Type" => "application/json"            ]        ]);        $customer = new CustomerService($client);        $token_request = $customer->authenticate([            'username'    => $username,            'password'    => $password,            'remember_me' => true        ]);        $token = $token_request['data']['id_token'];        return new self($token);    }    public function get_result(): array    {        return $this->result;    }    /**     * @throws UzpostException     */    public function send_request(        string $method,        string $endpoint,        array $params = [],        string $api_version = 'v1'    ): UzpostClient    {        try {            $fullEndpoint = "/api/{$api_version}/".ltrim($endpoint, '/');            $options = [                'headers' => [                    'Accept'        => 'application/json',                    'Content-Type' => 'application/json'                ]            ];            if ($endpoint !== 'customer/authenticate') {                $options['headers']['Authorization'] = 'Bearer ' . $this->apiKey;            }            if ($method === 'GET') {                $options['query'] = $params;            } else {                $options['json'] = $params;            }            $response = $this->httpClient->request($method, $fullEndpoint, $options);            $data = json_decode($response->getBody()->getContents(), true);            $this->logs[] = [                'timestamp' => date('Y-m-d H:i:s'),                'method'    => $method,                'endpoint'  => $fullEndpoint,                'params'    => $params,                'response'  => $data            ];            $this->result = $data;            return $this;        } catch (GuzzleException $e) {            $this->logs[] = [                'timestamp' => date('Y-m-d H:i:s'),                'method' => $method,                'endpoint' => $fullEndpoint,                'params' => $params,                'error' => $e->getMessage()            ];            throw new UzpostException($e->getMessage(), $e->getCode(), $e);        }    }    public function log(string $filename = 'uzpost_log.txt'): self    {        if (empty($this->logs)) {            return $this;        }        $logData = '';        foreach ($this->logs as $log) {            $logData .= '['.$log['timestamp'].'] '.$log['method'].' '.$log['endpoint']."\n";            $logData .= 'Params: '.json_encode($log['params'])."\n";            if (isset($log['response'])) {                $logData .= 'Response: '.json_encode($log['response'])."\n";            }            if (isset($log['error'])) {                $logData .= 'Error: '.$log['error']."\n";            }            $logData .= "---------------------------\n";        }        file_put_contents($filename, $logData, FILE_APPEND);        $this->logs = [];        return $this;    }}