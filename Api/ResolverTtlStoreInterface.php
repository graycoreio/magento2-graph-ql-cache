<?php

/**
 * Copyright © Graycore, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Graycore\GraphQlCache\Api;

interface ResolverTtlStoreInterface
{

    /**
     * Add a raw TTL value in seconds.
     *
     * @param int $ttl Time-to-live in seconds.
     */
    public function add(int $ttl): void;

    /**
     * Add TTL calculated as the number of seconds between now and a specific future date.
     *
     * If the date is null, it will be ignored.
     * 
     * If the date is a string, it will first be converted to the store's configured timezone and added.
     * 
     * If the date is in the past or now, adds 0.
     *
     * @param \DateTimeImmutable $date Target future date and time.
     */
    public function addForDate(\DateTimeImmutable | null | string $date): void;

    /**
     * Add TTL as the number of seconds between now and the **start of the given date** (00:00:00).
     *
     * If the date is null, it will be ignored.
     *
     * If the date is a string, it will first be converted to the store's configured timezone and added.
     * 
     * If the date is in the past or now, adds 0.
     *
     * @param $date Date whose start time is used for TTL calculation.
     */
    public function addForStartDate(\DateTimeImmutable | null | string $date): void;

    /**
     * Add TTL as the number of seconds between now and the **end of the given date** (23:59:59).
     *
     * If the date is null, it will be ignored.
     *
     * If the date is a string, it will first be converted to the store's configured timezone and added.
     * 
     * If the date is in the past or now, adds 0.
     *
     * @param \DateTimeImmutable $date Date whose end time is used for TTL calculation.
     */
    public function addForEndDate(\DateTimeImmutable | null | string $date): void;

    /**
     * Get all TTls from the configured resolvers.
     *
     * @return array
     */
    public function getAll(): array;
}
