<?php
declare(strict_types=1);

namespace srag\asq\Questions\Formula;

use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\Domain\Model\AbstractConfiguration;

/**
 * Class FormulaEditorConfiguration
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class FormulaEditorConfiguration extends AbstractConfiguration {

    public static function create() : FormulaEditorConfiguration {
        return new FormulaEditorConfiguration();
    }
    
    // Empty class as Formulaquestion has no clear divide between editor and display
    // So all data is stored in Scoring
    
    public function equals(AbstractValueObject $other): bool
    {
        return get_class($this) === get_class($other);
    }
}