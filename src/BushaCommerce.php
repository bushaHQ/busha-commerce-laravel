<?php

namespace Busha\Commerce;

use Busha\Commerce\Exceptions\InvalidPayloadException;
use Busha\Commerce\Exceptions\ServerErrorException;
use Busha\Commerce\Exceptions\UnauthorizedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;

/**
 *
 */
class BushaCommerce
{

    /**
     * @const  string
     */
    const BASE_URI = 'https://api.commerce.busha.co';

    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var string
     */
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = Config::get('busha.secret_key');
        $this->client = new Client([
            'base_uri' => self::BASE_URI,
            'headers' => [
                'Content-Type' => 'application/json',
                'X-BC-API-KEY' => $this->apiKey,
                'Accept' => 'application/json',
            ],
            'http_errors' => false
        ]);
    }

    /**
     * Set API Key
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return BushaCommerce
     */
    public function setApiKey(string $apiKey): static
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Do request to API.
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     * @return object
     */
    private function doRequest(string $method, string $uri, array $params = [])
    {
        $options  = ['body' => json_encode($params)];
        if (strtolower($method) == 'get'){
            $options  = ['query' => $params];
        }
        try{
            $response = $this->client->request($method, $uri, $options);
            $payload = json_decode($response->getBody()->getContents());
            return $this->parseResponse($payload, $response);
        }catch (GuzzleException $e){
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * Creates a new charge.
     *
     * @param array $params
     * @return object
     * @throws ServerErrorException
     */
    public function createCharge(array $params = []): object
    {
        $params['local_amount'] = strval($params['local_amount'] ?? 0);
        return $this->doRequest('post', 'charges', $params);
    }

    /**
     * List all Charges
     *
     * @param array $query
     * @return object
     * @throws ServerErrorException
     */
    public function listCharges(array $query = ['page' => 1, 'per_page' => 25]): object
    {
        return $this->doRequest('get', 'charges', $query);
    }

    /**
     *  Get a charge
     * @param string $chargeId
     * @return object
     * @throws ServerErrorException
     */
    public function getCharge(string $chargeId): object
    {
        return $this->doRequest('get', "charges/{$chargeId}");
    }


    /**
     * @param string $chargeId
     * @param string $reason
     * @return object
     * @throws ServerErrorException
     */
    public function resolveCharge(string $chargeId, string $reason): object
    {
        return $this->doRequest('post', "charges/{$chargeId}/resolve", ['context' => $reason]);
    }

    /**
     * Cancel charge
     * @param string $chargeId
     * @return object
     * @throws ServerErrorException
     */
    public function cancelCharge(string $chargeId): object {
        return $this->doRequest('put', "charges/{$chargeId}/cancel");
    }

    /**
     * @param array $params
     * @return object
     * @throws ServerErrorException
     */
    public function createPaymentLink(array $params = []): object
    {
        $params['local_amount'] = strval($params['local_amount'] ?? 0);
        return $this->doRequest('post', 'payment_links', $params);
    }

    /**
     * @param string $paymentLinkID
     * @param array $params
     * @return object
     * @throws ServerErrorException
     */
    public function updatePaymentLink(string $paymentLinkID, array $params = []): object
    {
        $params['local_amount'] = strval($params['local_amount'] ?? 0);
        return $this->doRequest('put', "payment_links/{$paymentLinkID}", $params);
    }

    /**
     * @param array $query
     * @return object
     * @throws ServerErrorException
     */
    public function listPaymentLinks(array $query = ['page' => 1, 'per_page' => 25]): object
    {
        return $this->doRequest('get', 'payment_links', $query);
    }

    /**
     * @param string $paymentLinkID
     * @return object
     * @throws ServerErrorException
     */
    public function getPaymentLink(string $paymentLinkID): object
    {
        return $this->doRequest('get', "payment_links/{$paymentLinkID}");
    }

    /**
     * @param string $paymentLinkID
     * @return object
     * @throws ServerErrorException
     */
    public function deletePaymentLink(string $paymentLinkID): object
    {
        return $this->doRequest('delete', "payment_links/{$paymentLinkID}");
    }

    /**
     * @param string $paymentLinkID
     * @return object
     * @throws ServerErrorException
     */
    public function togglePaymentLink(string $paymentLinkID): object
    {
        return $this->doRequest('patch', "payment_links/{$paymentLinkID}/active");
    }

    /**
     * @param string $paymentLinkID
     * @param array $params
     * @return object
     * @throws ServerErrorException
     */
    public function createPaymentLinkCharge(string $paymentLinkID, array $params = []): object
    {
        $params['local_amount'] = strval($params['local_amount'] ?? 0);
        return $this->doRequest('post', "payment_links/{$paymentLinkID}/charge", $params);
    }

    /**
     * @param array $params
     * @return object
     * @throws ServerErrorException
     */
    public function createInvoice(array $params = []): object
    {
        $params['local_amount'] = strval($params['local_amount'] ?? 0);
        return $this->doRequest('post', 'invoices', $params);
    }

    /**
     * @param array $query
     * @return object
     * @throws ServerErrorException
     */
    public function listInvoices(array $query = ['page' => 1, 'per_page' => 25]): object
    {
        return $this->doRequest('get', 'invoices', $query);
    }

    /**
     * @param string $invoiceId
     * @return object
     * @throws ServerErrorException
     */
    public function getInvoice(string $invoiceId): object
    {
        return $this->doRequest('get', "invoices/{$invoiceId}");
    }

    /**
     * @param string $invoiceId
     * @return object
     * @throws ServerErrorException
     */
    public function voidInvoice(string $invoiceId): object
    {
        return $this->doRequest('delete', "invoices/{$invoiceId}");
    }

    /**
     * @param string $invoiceId
     * @param array $params
     * @return object
     * @throws ServerErrorException
     */
    public function createInvoiceCharge(string $invoiceId, array $params = []): object
    {
        $params['local_amount'] = strval($params['local_amount'] ?? 0);
        return $this->doRequest('post', "invoices/{$invoiceId}/charge", $params);
    }

    /**
     * @param array $query
     * @return object
     * @throws ServerErrorException
     */
    public function listEvents(array $query = ['page' => 1, 'per_page' => 25]): object
    {
        return $this->doRequest('get', 'events', $query);
    }

    /**
     * @param string $eventId
     * @return object
     * @throws ServerErrorException
     */
    public function getEvent(string $eventId): object
    {
        return $this->doRequest('get', "events/{$eventId}");
    }

    /**
     * @param array $query
     * @return object
     * @throws ServerErrorException
     */
    public function listAddresses(array $query = ['page' => 1, 'per_page' => 25]): object
    {
        return $this->doRequest('get', 'addresses', $query);
    }

    /**
     * @param string $addressId
     * @return object
     * @throws ServerErrorException
     */
    public function getAddress(string $addressId): object
    {
        return $this->doRequest('get', "addresses/{$addressId}");
    }

    /**
     * @param array $params
     * @return object
     * @throws ServerErrorException
     */
    public function createAddress(array $params = []): object
    {
        return $this->doRequest('post', 'addresses', $params);
    }

    /**
     * @throws ServerErrorException
     * @throws UnauthorizedException
     * @throws InvalidPayloadException
     */
    private function parseResponse($response, $request){
        switch ($request->getStatusCode()) {
            case 201:
            case 200:
                return $response;
            case 400:
                throw new InvalidPayloadException($response->error->message);
            case 401:
                throw new UnauthorizedException($response->error->message);
            default:
                throw new ServerErrorException($response->error->message);
        }
    }

}