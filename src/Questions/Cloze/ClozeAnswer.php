<?php
declare(strict_types=1);

namespace srag\asq\Questions\Cloze;

use srag\asq\Domain\Model\Answer\Answer;

/**
 * Class ClozeAnswer
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class ClozeAnswer extends Answer {
    /**
     * @var ?array
     */
    protected $answers;
    
    public static function create(?array $answers = []) : ClozeAnswer {
        $object = new ClozeAnswer();
        $object->answers = $answers;
        return $object;
    }
    
    public function getAnswers() : ?array {
        return $this->answers;
    }
}