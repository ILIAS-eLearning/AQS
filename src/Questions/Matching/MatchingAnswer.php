<?php
declare(strict_types = 1);
namespace srag\asq\Questions\Matching;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class MatchingAnswer
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 * @package srag/asq
 * @author Adrian Lüthi <al@studer-raimann.ch>
 */
class MatchingAnswer extends AbstractValueObject
{

    /**
     * @var string[]
     */
    protected $matches;

    /**
     * @param array $matches
     */
    public function __construct(?array $matches = [])
    {
        $this->matches = $matches;
    }

    /**
     * @return ?array
     */
    public function getMatches() : ?array
    {
        return $this->matches;
    }

    /**
     * @return string
     */
    public function getAnswerString() : string
    {
        return is_null($this->matches) ? '' : implode(';', $this->matches);
    }
}
