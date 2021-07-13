<?php

namespace Drupal\wmpage_cache_cloudfront;

use Drupal\wmpage_cache\Cache;
use Drupal\wmpage_cache\Storage\StorageInterface;

class CloudFront implements StorageInterface
{
    /** @var CloudFrontInvalidator */
    protected $invalidator;
    /** @var StorageInterface */
    protected $storage;
    /** @var int */
    protected $concurrent = 50;

    public function __construct(
        CloudFrontInvalidator $invalidator,
        StorageInterface $storage
    ) {
        $this->invalidator = $invalidator;
        $this->storage = $storage;
    }

    public function remove(array $ids): void
    {
        $this->invalidate($ids);
        $this->storage->remove($ids);
    }

    public function flush(): void
    {
        $this->storage->flush();
        $this->invalidator->invalidate(['/*']);
    }

    public function load($id, $includeBody = true): Cache
    {
        return $this->storage->load($id, $includeBody);
    }

    public function loadMultiple(array $ids, $includeBody = true): \Iterator
    {
        return $this->storage->loadMultiple($ids, $includeBody);
    }

    public function set(Cache $item, array $tags): void
    {
        $this->storage->set($item, $tags);
    }

    public function getByTags(array $tags): array
    {
        return $this->storage->getByTags($tags);
    }

    public function getExpired($amount): array
    {
        $this->storage->getExpired($amount);
    }

    protected function invalidate(array $ids): void
    {
        // Invalidate in a foreach loop so we can leverage generators and
        // play nice with our memory when invalidating a whole bunch of items.
        //
        // Even though CloudFront does max 3000 concurrent invalidations..
        $paths = [];
        foreach ($this->storage->loadMultiple($ids, false) as $item) {
            /** @var Cache $item */
            if ($item->getExpiry() < time() + 60) {
                continue;
            }
            $paths[] = parse_url($item->getUri(), PHP_URL_PATH);

            if (count($paths) === $this->concurrent) {
                $this->invalidator->invalidate($paths);
                $paths = [];
            }
        }

        if ($paths) {
            $this->invalidator->invalidate($paths);
        }
    }
}
