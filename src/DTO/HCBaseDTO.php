<?php

declare(strict_types = 1);

namespace HoneyComb\Core\DTO;

/**
 * Class HCBaseDTO
 * @package HoneyComb\Core\DTO
 */
abstract class HCBaseDTO implements \JsonSerializable
{
    /**
     * @return array|mixed
     */
    final public function jsonSerialize()
    {
        return $this->jsonData();
    }

    /**
     * @return array
     */
    abstract protected function jsonData(): array;
}
