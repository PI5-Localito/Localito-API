<?php

namespace Lib\Storage;

interface Entity extends Hydratable
{
    /**
     * Get the identifier for the entity
     *
     * @return ?int
     */
    public function getId(): ?int;
}
