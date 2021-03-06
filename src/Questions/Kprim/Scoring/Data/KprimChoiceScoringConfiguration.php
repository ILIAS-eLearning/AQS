<?php
declare(strict_types = 1);

namespace srag\asq\Questions\Kprim\Scoring\Data;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class KprimChoiceScoringConfiguration
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 * @package srag/asq
 * @author Adrian Lüthi <al@studer-raimann.ch>
 */
class KprimChoiceScoringConfiguration extends AbstractValueObject
{

    /**
     * @var ?float
     */
    protected $points;

    /**
     * @var ?int
     */
    protected $half_points_at;

    /**
     * @param ?float $points
     * @param ?int $half_points_at
     */
    public function __construct(?float $points = null, ?int $half_points_at = null)
    {
        $this->points = $points;
        $this->half_points_at = $half_points_at;
    }

    /**
     * @return ?int
     */
    public function getPoints() : ?float
    {
        return $this->points;
    }

    /**
     * @return ?int
     */
    public function getHalfPointsAt() : ?int
    {
        return $this->half_points_at;
    }
}
