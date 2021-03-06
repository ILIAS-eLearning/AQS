<?php
declare(strict_types=1);

namespace srag\asq\Questions\Numeric\Scoring\Data;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class NumericScoringConfiguration
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class NumericScoringConfiguration extends AbstractValueObject
{
    /**
     * @var ?float
     */
    protected $points;
    /**
     * @var ?float
     */
    protected $lower_bound;
    /**
     * @var ?float
     */
    protected $upper_bound;

    /**
     * @param float $points
     * @param float $lower_bound
     * @param float $upper_bound
     */
    public function __construct(
        ?float $points = null,
        ?float $lower_bound = null,
        ?float $upper_bound = null
    ) {
        $this->points = $points;
        $this->lower_bound = $lower_bound;
        $this->upper_bound = $upper_bound;
    }

    /**
     * @return float|NULL
     */
    public function getPoints() : ?float
    {
        return $this->points;
    }

    /**
     * @return float|NULL
     */
    public function getLowerBound() : ?float
    {
        return $this->lower_bound;
    }

    /**
     * @return float|NULL
     */
    public function getUpperBound() : ?float
    {
        return $this->upper_bound;
    }
}
