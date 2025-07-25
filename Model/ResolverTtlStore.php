<?php

/**
 * Copyright © Graycore, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Graycore\GraphQlCache\Model;

use DateTimeImmutable;
use DateTimeZone;
use Graycore\GraphQlCache\Api\ResolverTtlStoreInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class ResolverTtlStore implements ResolverTtlStoreInterface
{
    /**
     * @var int[] List of collected TTL values (in seconds).
     */
    private array $_data = [];

    public function __construct(private TimezoneInterface $timezone) {}

    /**
     * @inheritDoc
     */
    public function add(int $ttl): void
    {
        $this->_data[] = $ttl;
    }

    private function addImmediateExpiration()
    {
        $this->_data[] = 1;
    }

    /**
     * @inheritDoc
     */
    public function addForDate(\DateTimeImmutable | null | string $date): void
    {
        if (!$date) {
            return;
        }

        if (is_string($date)) {
            $date = new DateTimeImmutable($date, new DateTimeZone($this->timezone->getConfigTimezone()));
        }

        $now = new \DateTimeImmutable();
        $ttl = $date->getTimestamp() - $now->getTimestamp();
        if ($ttl <= 0) {
            $this->addImmediateExpiration();
            return;
        }

        $this->_data[] =  $ttl;
    }

    /**
     * @inheritDoc
     */
    public function addForStartDate(\DateTimeImmutable | null | string $date): void
    {
        if (!$date) {
            return;
        }

        if (is_string($date)) {
            $date = new DateTimeImmutable($date, new DateTimeZone($this->timezone->getConfigTimezone()));
        }

        $date = $date->setTime(0, 0, 0);

        $now = new \DateTimeImmutable();
        $ttl = $date->getTimestamp() - $now->getTimestamp();
        if ($ttl <= 0) {
            $this->addImmediateExpiration();
            return;
        }

        $this->_data[] =  $ttl;
    }

    /**
     * @inheritDoc
     */
    public function addForEndDate(\DateTimeImmutable | null | string $date): void
    {
        if (!$date) {
            return;
        }

        if (is_string($date)) {
            $date = new DateTimeImmutable($date, new DateTimeZone($this->timezone->getConfigTimezone()));
        }

        $date = $date->setTime(23, 59, 59);

        $now = new \DateTimeImmutable();
        $ttl = $date->getTimestamp() - $now->getTimestamp();
        if ($ttl <= 0) {
            $this->addImmediateExpiration();
            return;
        }

        $this->_data[] =  $ttl;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->_data;
    }
}
