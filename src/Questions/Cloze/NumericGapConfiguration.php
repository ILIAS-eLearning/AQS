<?php
declare(strict_types=1);

namespace srag\asq\Questions\Cloze;

/**
 * Class NumericGapConfiguration
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class NumericGapConfiguration extends ClozeGapConfiguration {
    /**
     * @var ?float
     */
    protected $value;
    
    /**
     * @var ?float
     */
    protected $upper;
    
    /**
     * @var ?float
     */
    protected $lower;
    
    /**
     * @var ?float
     */
    protected $points;
    
    /**
     * @var int
     */
    protected $field_length;
    
    public static function Create(?float $value = null, ?float $upper = null, ?float $lower = null, ?float $points = null, int $field_length = 80) {
        $object = new NumericGapConfiguration();
        $object->value = $value;
        $object->upper = $upper;
        $object->lower = $lower;
        $object->points = $points;
        $object->field_length = $field_length;
        return $object;
    }
    
    /**
     * @return ?float
     */
    public function getValue() : ?float
    {
        return $this->value;
    }
    
    /**
     * @return ?float
     */
    public function getUpper() : ?float
    {
        return $this->upper;
    }
    
    /**
     * @return ?float
     */
    public function getLower() : ?float
    {
        return $this->lower;
    }
    
    /**
     * @return ?int
     */
    public function getPoints() : ?float
    {
        return $this->points;
    }
    
    /**
     * @return int
     */
    public function getFieldLength() : int
    {
        return $this->field_length;
    }
    
    public function getMaxPoints(): float
    {
        return $this->points;
    }
}