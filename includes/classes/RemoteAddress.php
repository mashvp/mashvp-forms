<?php

namespace Mashvp\Forms;

/**
 * Based on Zend's RemoteAddress class
 *
 * https://github.com/zendframework/zend-http/blob/master/src/PhpEnvironment/RemoteAddress.php
 */
class RemoteAddress
{
    protected $useProxy = false;
    protected $trustedProxies = [];
    protected $proxyHeader = 'HTTP_X_FORWARDED_FOR';

    public function getIpAddress()
    {
        $ip = $this->getIpAddressFromProxy();

        if ($ip) {
            return $ip;
        }

        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return '';
    }

    protected function getIpAddressFromProxy()
    {
        if (!$this->useProxy
            || (isset($_SERVER['REMOTE_ADDR']) && !in_array($_SERVER['REMOTE_ADDR'], $this->trustedProxies))
        ) {
            return false;
        }

        $header = $this->proxyHeader;
        if (!isset($_SERVER[$header]) || empty($_SERVER[$header])) {
            return false;
        }

        $ips = explode(',', $_SERVER[$header]);
        $ips = array_map('trim', $ips);
        $ips = array_diff($ips, $this->trustedProxies);

        if (empty($ips)) {
            return false;
        }

        $ip = array_pop($ips);

        return $ip;
    }
}
