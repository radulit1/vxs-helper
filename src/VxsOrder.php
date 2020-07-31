<?php

namespace VxsBill;

use VxsBill\Exceptions\CouldNotLoadCredentials;
use VxsBill\Exceptions\InvalidOrderParams;
use VxsBill\Exceptions\InvalidOrderRequest;
use VxsBill\Exceptions\InvalidOrderResponse;
use GuzzleHttp\Client;


class VxsOrder
{

    protected $credentials;
    protected $client;

    public function __construct(VxsCredentials $credentials, Client $client)
    {
        $this->credentials = $credentials;
        $this->client = $client;
    }

    protected function request(string $url, array $params)
    {
        $url = $url . "?" . http_build_query($params);

        $response =  $this->client->get($url);

        if ($response->getStatusCode() != 200) {
            throw new \Exception('Invalid request');
        }

        return $response;
    }

    public function create(array $orderParams)
    {
        try {
            $this->validateParams($orderParams);
            $params = array_merge($orderParams, $this->credentials->toArray());
            $response = $this->request(VXS::VXS_ENDPOINT_URL . 'create_order.php3', $params);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

       return $response;
    }

    public function check($orderId)
    {
        try {
            $params = [
               'order_id' => $orderId
            ];
            $response = $this->request(VXS::VXS_ENDPOINT_URL . 'check_order.php3', $params);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return $response;
    }
    public function validateParams(array $orderParams)
    {
        if (!isset($orderParams['price'])) {
            throw InvalidOrderParams::priceNotSet();
        }

        if (!is_integer($orderParams['price'])) {
            throw InvalidOrderParams::invalidPriceFormat();
        }

        if (!isset($orderParams['description'])) {
            throw InvalidOrderParams::descriptionNotSet();
        }

        if (!isset($orderParams['currency'])) {
            throw InvalidOrderParams::currencyNotSet();
        }

        return true;
    }

    public function parseOrderResponse($response)
    {

        $content = $this->parseVxsResponse($response);

        switch ($this->lowercaseAndRemoveSpaces($content[0])) {
            case "ok":
                return $content[1];
                break;
            case "error":
            default:
                throw InvalidOrderResponse::unsuccessfulResponse($response);

                break;
        }
    }
    public function parseOrderCheckResponse($response)
    {

        $content = $this->parseVxsResponse($response);



        switch ($this->lowercaseAndRemoveSpaces($content[0])) {
            case "ok":
                return [
                    "success" => true,
                    "status" => trim($content[2]),
                    "transactionid" => trim($content[1]),
                    "message" => trim($content[3]),
                    "email" => trim($content[4])
                ];
                break;
            case "error":
            default:
                return [
                    "success" => false,
                    "message" => $content[1]
                ];

                break;
        }
    }

    public function parseVxsResponse(string $string) : array
    {
        return preg_split("/:/", $string);
    }
    public function lowercaseAndRemoveSpaces(string $string) :string
    {
        return strtolower(trim($string));
    }
}
