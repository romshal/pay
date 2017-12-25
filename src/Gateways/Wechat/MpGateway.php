<?php

namespace Yansongda\Pay\Gateways\Wechat;

use Symfony\Component\HttpFoundation\Request;
use Yansongda\Pay\Log;
use Yansongda\Supports\Collection;
use Yansongda\Supports\Str;

class MpGateway extends Gateway
{
    /**
     * Pay an order.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param string $endpoint
     * @param array  $payload
     *
     * @return Collection
     */
    public function pay($endpoint, $payload): Collection
    {
        $payRequest = [
            'appId'     => $this->config->get('app_id'),
            'timeStamp' => strval(time()),
            'nonceStr'  => Str::random(),
            'package'   => 'prepay_id='.$this->preOrder('pay/unifiedorder', $payload)->prepay_id,
            'signType'  => 'MD5',
        ];
        $payRequest['paySign'] = Support::generateSign($payRequest, $this->config->get('key'));

        Log::debug('Paying A JSAPI Order:', [$endpoint, $payRequest]);

        return new Collection($payRequest);
    }

    /**
     * Get trade type config.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @return string
     */
    protected function getTradeType(): string
    {
        return 'JSAPI';
    }
}
