<?php

namespace OmarPackage;

use Cache\Bridge\SimpleCache\SimpleCacheBridge;
use Cache\Bridge\SimpleCache\Exception\InvalidArgumentException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException as CacheInvalidArgumentException;

class CustomCacheBridge extends SimpleCacheBridge
{
    /**
     * @var int
     */
    protected $ttl = null;

    /**
     * CacheBridge constructor.
     *
     * @param \Psr\Cache\CacheItemPoolInterface $cacheItemPool
     * @param int $defaultTtl
     */
    public function __construct(CacheItemPoolInterface $cacheItemPool, int $defaultTtl = 3600)
    {
        parent::__construct($cacheItemPool);
        $this->ttl = $defaultTtl;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = null): bool
    {
        if ($this->ttl && is_null($ttl)) {
            $ttl = $this->ttl;
        }

        try {
            $item = $this->cacheItemPool->getItem($key);
            $item->expiresAfter($ttl);
        } catch (CacheInvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }

        $item->set($value);

        return $this->cacheItemPool->save($item);
    }
}

?>