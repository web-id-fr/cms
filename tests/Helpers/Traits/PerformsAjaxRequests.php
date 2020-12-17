<?php

namespace Webid\Cms\Tests\Helpers\Traits;

trait PerformsAjaxRequests
{
    /** @var string[] */
    protected $ajaxHeaders = [
        'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ];

    /**
     * @param $uri
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Testing\TestResponse
     */
    protected function ajaxPost($uri, array $data = [], array $headers = [])
    {
        $headers = array_merge($this->ajaxHeaders, $headers);
        return $this->json('POST', $uri, $data, $headers);
    }

    /**
     * @param $uri
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Testing\TestResponse
     */
    protected function ajaxGet($uri, array $data = [], array $headers = [])
    {
        $headers = array_merge($this->ajaxHeaders, $headers);
        return $this->json('GET', $uri, $data, $headers);
    }

    /**
     * @param $uri
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Testing\TestResponse
     */
    protected function ajaxPut($uri, array $data = [], array $headers = [])
    {
        $headers = array_merge($this->ajaxHeaders, $headers);
        return $this->json('PUT', $uri, $data, $headers);
    }

    /**
     * @param $uri
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Testing\TestResponse
     */
    protected function ajaxDelete($uri, array $data = [], array $headers = [])
    {
        $headers = array_merge($this->ajaxHeaders, $headers);
        return $this->json('DELETE', $uri, $data, $headers);
    }
}
