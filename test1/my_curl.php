<?php

class myCURL
{
    protected $headers;
    protected $user_agent;
    protected $compression;
    protected $cookie_file;
    protected $proxy;

    public static function &get_instance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new myCURL();
        }
        return $instance;
    }

    public function __construct($use_cookie = TRUE, $cookie_file = 'cookie.txt', $compression = 'gzip', $proxy = '')
    {
        $this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
        $this->headers[] = 'Connection: Keep-Alive';
        $this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        $this->user_agent = 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36';
        $this->compression = $compression;
        $this->proxy = $proxy;
        $this->use_cookie = $use_cookie;
        if ($this->use_cookie == TRUE) $this->cookie($cookie_file);
    }

    protected function cookie($cookie_file)
    {
        if (file_exists($cookie_file)) {
            $this->cookie_file = $cookie_file;
        } else {
            fopen($cookie_file, 'w') or $this->error('The cookie file could not be opened. Make sure this directory has the correct permissions');
            $this->cookie_file = $cookie_file;
            fclose($this->cookie_file);
        }
    }

    public function get($url)
    {
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
        if ($this->use_cookie == TRUE) {
            curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
            curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
        }
        curl_setopt($process, CURLOPT_ENCODING, $this->compression);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($process, CURLOPT_CAINFO, NULL);
        curl_setopt($process, CURLOPT_CAPATH, NULL);
        $return = curl_exec($process);
        curl_close($process);
        return $return;
    }

    public function post($url, $data)
    {
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($process, CURLOPT_HEADER, 1);
        curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
        if ($this->use_cookie == TRUE) {
            curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
            curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
        }
        curl_setopt($process, CURLOPT_ENCODING, $this->compression);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POSTFIELDS, $data);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($process, CURLOPT_CAINFO, NULL);
        curl_setopt($process, CURLOPT_CAPATH, NULL);
        $return = curl_exec($process);
        curl_close($process);
        return $return;
    }

    protected function error($error)
    {
        echo "Error: <br><i>$error</i>";
        die;
    }
}

