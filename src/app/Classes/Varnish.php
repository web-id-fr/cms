<?php

namespace Webid\Cms\Src\App\Classes;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

Class VarnishCustom
{
    /**
     * @param string|array $host
     * @param string $url
     *
     * @return \Symfony\Component\Process\Process
     */
    public function flush($host = null, string $url = null): Process
    {
        $host = $this->getHosts($host);

        $command = $this->generateBanCommand($host, $url);

        return $this->executeCommand($command);
    }

    /**
     * @param array|string $host
     *
     * @return array
     */
    protected function getHosts($host = null): array
    {
        $host = $host ?? config('varnish.host');

        if (! is_array($host)) {
            $host = [$host];
        }

        return $host;
    }

    /**
     * @param array $hosts
     * @param string|null $url
     *
     * @return array
     */
    public function generateBanCommand(array $hosts, string $url = null): array
    {
        $hostsString = collect($hosts)
            ->map(function (string $host) {
                return "(^{$host}$)";
            })
            ->implode('|');

        $config = config('varnish');
        $requestHost = "req.http.host ~ $hostsString";

        return ['sudo', 'varnishadm', '-S', $config['administrative_secret'], '-T', '127.0.0.1:' . $config['administrative_port'], 'ban', $requestHost];
    }

    /**
     * @param array $command
     *
     * @return Process
     */
    protected function executeCommand(array $command): Process
    {
        $process = new Process($command);

        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process;
    }
}
