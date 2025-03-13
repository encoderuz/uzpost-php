<?php/** Abdukodir Khojiyev* Backend Developer* Project: uzpost* Date:  11/03/25*/namespace Encoderuz\Uzpost\Services;use Encoderuz\Uzpost\Contracts\JurisdictionServiceInterface;use Encoderuz\Uzpost\Exceptions\UzpostException;use Encoderuz\Uzpost\Http\UzpostClient;class JurisdictionService implements JurisdictionServiceInterface{    protected UzpostClient $client;    public function __construct(        UzpostClient $client    ) {        $this->client = $client;    }    /**     * @throws UzpostException     */    public function get_jurisdictions(): array    {        return $this->client->send_request('GET', 'jurisdiction/choose/list', [], "v2")->get_result();    }}