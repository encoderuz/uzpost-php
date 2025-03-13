<?php/** Abdukodir Khojiyev* Backend Developer* Project: uzpost* Date:  11/03/25*/namespace Encoderuz\Uzpost\Services;use Encoderuz\Uzpost\Contracts\CustomerServiceInterface;use Encoderuz\Uzpost\Exceptions\UzpostException;use Encoderuz\Uzpost\Http\UzpostClient;class CustomerService implements CustomerServiceInterface{    protected UzpostClient $client;    public function __construct(        $client    )    {        $this->client = $client;    }    /**     * @throws UzpostException     */    public function sign_up(array $customerData): array    {        return $this->client->send_request('POST', 'customer/register', $customerData)->get_result();    }    /**     * @throws UzpostException     */    public function authenticate(array $credentials): array    {        return $this->client->send_request('POST', 'customer/authenticate', $credentials)->get_result();    }    /**     * @throws UzpostException     */    public function get_customer_data(): array    {        return $this->client->send_request('GET', 'customer/account')->get_result();    }}