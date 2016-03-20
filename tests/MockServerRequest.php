<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

class MockServerRequest implements ServerRequestInterface
{
    public $get = [];
    public $post = [];

    public function getProtocolVersion()
    {
        return '1.0';
    }

    public function withProtocolVersion($version)
    {
        return $this;
    }

    public function getHeaders()
    {
        return [];
    }

    public function hasHeader($name)
    {
        return $this;
    }

    public function getHeader($name)
    {
        return [];
    }

    public function getHeaderLine($name)
    {
        return '';
    }

    public function withHeader($name, $value)
    {
        return $this;
    }

    public function withAddedHeader($name, $value)
    {
        return $this;
    }

    public function withoutHeader($name)
    {
        return $this;
    }

    public function getBody()
    {
        return $this;
    }

    public function withBody(StreamInterface $body)
    {
        return $this;
    }

    public function getRequestTarget()
    {
        return '';
    }

    public function withRequestTarget($requestTarget)
    {
        return $this;
    }

    public function getMethod()
    {
        return '';
    }

    public function withMethod($method)
    {
        return $this;
    }

    public function getUri()
    {
        return null;
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        return $this;
    }

    public function getServerParams()
    {
        return [];
    }

    public function getCookieParams()
    {
        return [];
    }

    public function withCookieParams(array $cookies)
    {
        return $this;
    }

    public function getQueryParams()
    {
        return $this->get;
    }

    public function withQueryParams(array $query)
    {
        return $this;
    }

    public function getUploadedFiles()
    {
        return [];
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        return $this;
    }

    public function getParsedBody()
    {
        return $this->post;
    }

    public function withParsedBody($data)
    {
        return $this;
    }

    public function getAttributes()
    {
        return [];
    }

    public function getAttribute($name, $default = null)
    {
        return null;
    }

    public function withAttribute($name, $value)
    {
        return $this;
    }

    public function withoutAttribute($name)
    {
        return $this;
    }
}

