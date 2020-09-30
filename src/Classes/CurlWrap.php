<?php

namespace gyaani\guy\Classes;

use phpDocumentor\Reflection\Types\Integer;

class CurlWrap
{
    public $page;
    public $error;
    public $info;
    public $requestSent;
    public $options = [
        'ssl' => false,
        'saveCookie' => false,
        'loadCookie' => false,
        'diagnostic' => true,
        'autoreferer' => true
    ];

    private $ch;
    private $url;
    private $referer;
    private $addHeaders = [];
    private $postVars;
    private $userAgent  = "The name is bot. Web bot.";


    function exec()
    {

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        if ($this->options['handlessl']) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        curl_setopt($this->ch, CURLOPT_USERAGENT, $this->userAgent);

        if ($this->options['diagnostic']) {
            curl_setopt($this->ch, CURLOPT_HEADER, 1); // header in output
            curl_setopt($this->ch, CURLINFO_HEADER_OUT, true); // request string in header
        }
        if ($this->addHeaders) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->addHeaders);
        }
        if (!$this->referer && $this->options['autoreferer']) {
            $this->referer = $this->extractBaseUrl($this->url);
        }
        if ($this->referer) {
            curl_setopt($this->ch, CURLOPT_REFERER, $this->referer);
        }
        if ($this->postVars) {
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS,$this->postVars);
        }
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_MAXREDIRS, 4);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        $this->page = curl_exec($this->ch);

        if ($this->options['diagnostic']) {
            $this->info = curl_getinfo($this->ch);
            $this->requestSent = $this->info['request_header'];
            $this->error = curl_error($this->ch);
        }
        curl_close($this->ch);
        return $this->page;
    }

    function headers($headers)
    {
        $this->addHeaders = array_merge($this->addHeaders, $headers);
        return $this;
    }

    function post($postVars, $jsonPost = false)
    {
        if ($postVars) {
            if ($jsonPost) {
                $postVars = json_encode($postVars);
                $this->addHeaders[] = 'Content-Type: application/json';
            }
            $this->postVars = http_build_query($postVars);
        }
        return $this;
    }

    /**
     * CurlWrap constructor.
     * @param array $options
     * 'handlessl' - workaround for handling https. risk of mitm. |
     * 'savecookie' - save cookie in file |
     * 'loadcookie' - load cookie from file |
     * 'diagnostic' - return errors, header and request outs |
     */
    public function __construct(array $options=[])
    {
        $this->options = array_merge($this->options, $options);
    }

    public function url($url)
    {
        $this->url = $url;
        return $this;
    }

    public function referer($referer)
    {
        $this->referer = $referer;
        return $this;
    }

    public function useragent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    private function extractBaseUrl($url)
    {
        return parse_url($url,PHP_URL_HOST);
    }


}
