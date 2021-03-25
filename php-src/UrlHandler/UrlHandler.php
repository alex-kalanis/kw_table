<?php

namespace kalanis\kw_table\UrlHandler;


use ArrayAccess;
use kalanis\kw_input\Interfaces\IEntry;
use kalanis\kw_input\Interfaces\IInputs;


/**
 * Class UrlHandler
 * @package kalanis\kw_table\UrlHandler
 * Class for basic work with URL params. It allows updating params when they come from string representation.
 * It has internal representation of URL.
 */
class UrlHandler implements ArrayAccess
{
    /** @var string */
    protected $url = '';
    /** @var string */
    protected $urlPath = '';
    /** @var string[] */
    protected $getData = [];

    public function __construct(?IInputs $inputs)
    {
        if ($inputs) {
            $server = $inputs->intoKeyObjectArray($inputs->getIn('REQUEST_URI', [IEntry::SOURCE_SERVER]));
            if (isset($server['REQUEST_URI'])) {
                $this->setUrl($server['REQUEST_URI']);
            }
        }
    }

    public function setUrl(string $url): self
    {
        if (empty($url)) {
            return $this;
        }
        // REQUEST_URI which begins on two and more slashes might can be understand as relative url with FQDN
        $this->url = preg_replace('#^(//+)#', '/', $url);
        $parts = parse_url($this->url);
        $this->urlPath = $parts['path'];
        if (!isset($parts['query'])) {
            $parts['query'] = '';
        }
        $this->getData = $this->http_parse_query($parts['query']);
        return $this;
    }

    public function rebuildUrl(): self
    {
        $parts = parse_url($this->url);
        if (!isset($parts['query'])) {
            $parts['query'] = '';
        }
        $queryArray = $this->http_parse_query($parts['query']);
        foreach ($this->getData as $paramName => $paramValue) {
            $queryArray[$paramName] = $paramValue;
        }
        foreach ($queryArray as $paramName => $paramValue) {
            if (!isset($this->getData[$paramName])) {
                unset($queryArray[$paramName]);
            }
        }
        $parts['query'] = http_build_query($queryArray);
        $this->url = $this->buildUrl($parts);
        return $this;
    }

    /**
     * Returns an address.
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Parses http query string into an array
     *
     * @author Alxcube <alxcube@gmail.com>
     *
     * @param string $queryString String to parse
     * @param string $argSeparator Query arguments separator
     * @param integer $decType Decoding type
     * @return array
     */
    protected function http_parse_query(string $queryString, string $argSeparator = '&', int $decType = PHP_QUERY_RFC1738): array
    {
        if (empty($queryString)) { return []; }
        $result             = [];
        $parts              = explode($argSeparator, $queryString);

        foreach ($parts as $part) {
            list($paramName, $paramValue)   = array_pad(explode('=', $part, 2), 2, '');

            switch ($decType) {
                case PHP_QUERY_RFC3986:
                    $paramName      = rawurldecode($paramName);
                    $paramValue     = rawurldecode($paramValue);
                    break;

                case PHP_QUERY_RFC1738:
                default:
                    $paramName      = urldecode($paramName);
                    $paramValue     = urldecode($paramValue);
                    break;
            }


            if (preg_match_all('/\[([^\]]*)\]/m', $paramName, $matches)) {
                $paramName      = substr($paramName, 0, strpos($paramName, '['));
                $keys           = array_merge([$paramName], $matches[1]);
            } else {
                $keys           = [$paramName];
            }

            $target         = &$result;

            foreach ($keys as $index) {
                if ($index === '') {
                    if (isset($target)) {
                        if (is_array($target)) {
                            $intKeys        = array_filter(array_keys($target), 'is_int');
                            $index  = count($intKeys) ? max($intKeys)+1 : 0;
                        } else {
                            $target = [$target];
                            $index  = 1;
                        }
                    } else {
                        $target         = [];
                        $index          = 0;
                    }
                } elseif (isset($target[$index]) && !is_array($target[$index])) {
                    $target[$index] = [$target[$index]];
                }

                $target         = &$target[$index];
            }

            if (is_array($target)) {
                $target[]   = $paramValue;
            } else {
                $target     = $paramValue;
            }
        }

        return $result;
    }

    /**
     * Build a URL from parse_url parts. The generated URL will be a relative URL if a scheme or host are not provided.
     * @param string[] $parts Array of parse_url parts
     * @return string
     */
    protected function buildUrl(array $parts): string
    {
        $url = $scheme = '';

        if (isset($parts['scheme'])) {
            $scheme = $parts['scheme'];
            $url .= $scheme . ':';
        }

        if (isset($parts['host'])) {
            $url .= '//';
            if (isset($parts['user'])) {
                $url .= $parts['user'];
                if (isset($parts['pass'])) {
                    $url .= ':' . $parts['pass'];
                }
                $url .= '@';
            }

            $url .= $parts['host'];

            // Only include the port if it is not the default port of the scheme
            if (isset($parts['port'])
                && !(($scheme == 'http' && $parts['port'] == 80) || ($scheme == 'https' && $parts['port'] == 443))
            ) {
                $url .= ':' . $parts['port'];
            }
        }

        // Add the path component if present
        if (isset($parts['path']) && 0 !== strlen($parts['path'])) {
            // Always ensure that the path begins with '/' if set and something is before the path
            if ($url && $parts['path'][0] != '/' && substr($url, -1) != '/') {
                $url .= '/';
            }
            $url .= $parts['path'];
        }

        // Add the query string if present
        if (isset($parts['query'])) {
            $url .= '?' . $parts['query'];
        }

        // Ensure that # is only added to the url if fragment contains anything.
        if (isset($parts['fragment'])) {
            $url .= '#' . $parts['fragment'];
        }

        return $url;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->getData[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->getData[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->getData[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->getData[$offset]);
    }
}
