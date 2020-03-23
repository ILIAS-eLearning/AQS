<?php
declare(strict_types=1);

namespace srag\asq\Questions\Ordering;

use srag\asq\Domain\Model\Answer\Answer;

/**
 * Class OrderingAnswer
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class OrderingAnswer extends Answer {
    /**
     * @var ?int[]
     */
    protected $selected_order;
    
    public static function create(?array $selected_order = null) : OrderingAnswer {
        $object = new OrderingAnswer();
        $object->selected_order = $selected_order;
        return $object;
    }
    
    public function getSelectedOrder() {
        return $this->selected_order;
    }
}