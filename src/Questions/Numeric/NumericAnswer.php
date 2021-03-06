<?php
declare(strict_types = 1);
namespace srag\asq\Questions\Numeric;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class NumericAnswer
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 * @package srag/asq
 * @author Adrian Lüthi <al@studer-raimann.ch>
 */
class NumericAnswer extends AbstractValueObject
{

    /**
     * @var ?float
     */
    protected $value;

    /**
     * @param float $value
     */
    public function __construct(?float $value = null)
    {
        $this->value = $value;
    }

    /**
     * @return float|NULL
     */
    public function getValue() : ?float
    {
        return $this->value;
    }
}
