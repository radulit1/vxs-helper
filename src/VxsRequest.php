<?php


namespace VxsBill;




abstract class VxsRequest
{
    protected function request(string $url, array $params)
    {
        return Http::get($url, $params);
    }

}
