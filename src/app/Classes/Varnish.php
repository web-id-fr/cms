<?php

namespace Webid\Cms\Src\App\Classes;

use Spatie\Varnish\Varnish;

Class VarnishCustom extends Varnish
{
    /**
     * @param array $hosts
     * @param string|null $url
     *
     * @return string
     *
     */
    public function generateBanCommand(array $hosts, string $url = null): string
    {
        $hostsString = collect($hosts)
            ->map(function (string $host) {
                return $host;
            })
            ->implode('|');

        $config = config('varnish');

        return "sudo varnishadm -S {$config['administrative_secret']} -T 127.0.0.1:{$config['administrative_port']} ban 'req.http.host ~ $hostsString'";
    }
}
