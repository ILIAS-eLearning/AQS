<?php
declare(strict_types=1);

namespace srag\asq\Questions\TextSubset;

use srag\asq\Domain\Model\Answer\Answer;

/**
 * Class TextSubsetAnswer
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class TextSubsetAnswer extends Answer {
    /**
     * @var ?int[]
     */
    protected $answers;
    
    public static function create(?array $answers = null) {
        $object = new TextSubsetAnswer();
        $object->answers = $answers;
        return $object;
    }
    
    public function getAnswers() : ?array {
        return $this->answers;
    }
}