<?php
declare(strict_types=1);

namespace srag\asq\Questions\ErrorText\Scoring\Data;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class ErrorTextScoringConfiguration
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class ErrorTextScoringConfiguration extends AbstractValueObject
{
    /**
     * @var ?float
     */
    protected $points_wrong;

    public function __construct(?float $points_wrong = null)
    {
        $this->points_wrong = $points_wrong;
    }

    /**
     * @return int
     */
    public function getPointsWrong() : ?float
    {
        return $this->points_wrong;
    }
}
