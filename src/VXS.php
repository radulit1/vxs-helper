<?php
namespace VxsBill;


use GuzzleHttp\Client;

class VXS
{
    const VXS_ENDPOINT_URL = "https://secure.vxsbill.com/";
    protected $credentials;
    protected $client;
    protected $config = [
        "css" => [
            'layout'    =>  10,
            'color_bg'  =>  '171717',
            'color_fg'  =>  'ffffff',
            'color_fn'  =>  'ffffff',
            'color_bf'  =>  'ffffff',
            'color_bb'  =>  '00c100',
            'border_no' =>  3
        ]
    ];


    public function __construct(Client $client, array $params)
    {
        $this->credentials = new VxsCredentials($params);
        $this->client = $client;
    }

    public function createOrder(array $orderParams)
    {

        try {
            $order = new VxsOrder($this->credentials, $this->client);
            $response = $order->create($orderParams);

            if ($response->getStatusCode() != 200) {
                throw new \Exception('Invalid request');
            }

            return intval($order->parseOrderResponse($response->getBody()->getContents()));
        } catch (\Exception $exception) {
            throw new \Exception("Something went wrong. Please try again");
        }

    }

    public function checkOrder(int $orderId)
    {
        try {
            $order = new VxsOrder($this->credentials, $this->client);
            $response = $order->check($orderId);

            return $order->parseOrderCheckResponse($response->getBody()->getContents());
        } catch (\Exception $exception) {

            throw new \Exception("Something went wrong. Please try again");
        }
    }

    public function getRedirectUrl($orderId)
    {
        $url = self::VXS_ENDPOINT_URL . 'ezbill.php3?';
        $params = [
            'oreder_id' => $orderId,
            'site' => $this->credentials->getSite()
        ];

        $params = array_merge($params, $this->config['css']);

        return $url . http_build_query($params);
    }
    public static function notifySlack($message)
    {
        VXSNotification::slack($message);
    }

}
