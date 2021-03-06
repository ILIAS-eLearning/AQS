<?php
declare(strict_types=1);

namespace srag\asq\Questions\Formula\Scoring\Data;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class FormulaScoringVariable
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class FormulaScoringVariable extends AbstractValueObject
{
    const VAR_MIN = 'fsv_min';
    const VAR_MAX = 'fsv_max';
    const VAR_UNIT = 'fsv_unit';
    const VAR_MULTIPLE_OF = 'fsv_multiple_of';

    /**
     * @var ?float
     */
    protected $min;

    /**
     * @var ?float
     */
    protected $max;

    /**
     * @var ?string
     */
    protected $unit;

    /**
     * @var ?float
     */
    protected $multiple_of;

    /**
     * @param ?float $min
     * @param ?float $max
     * @param ?string $unit
     * @param ?float $divisor
     */
    public function __construct(
        ?float $min,
        ?float $max,
        ?string $unit,
        ?float $multiple_of
    ) {
        $this->min = $min;
        $this->max = $max;
        $this->unit = $unit;
        $this->multiple_of = $multiple_of;
    }

    /**
     * @return ?float
     */
    public function getMin() : ?float
    {
        return $this->min;
    }

    /**
     * @return ?float
     */
    public function getMax() : ?float
    {
        return $this->max;
    }

    /**
     * @return ?string
     */
    public function getUnit() : ?string
    {
        return $this->unit;
    }

    /**
     * @return ?float
     */
    public function getMultipleOf() : ?float
    {
        return $this->multiple_of;
    }

    /**
     * @return array
     */
    public function getAsArray() : array
    {
        return [
            self::VAR_MIN => $this->min,
            self::VAR_MAX => $this->max,
            self::VAR_UNIT => $this->unit,
            self::VAR_MULTIPLE_OF => $this->multiple_of
        ];
    }

    /**
     * @param array $units
     * @return bool
     */
    public function isComplete() : bool
    {
        return !is_null($this->getMax()) &&
               !is_null($this->getMin()) &&
               !is_null($this->getMultipleOf());
    }
}
