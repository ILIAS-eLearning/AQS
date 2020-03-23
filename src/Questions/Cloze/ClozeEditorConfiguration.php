<?php
declare(strict_types=1);

namespace srag\asq\Questions\Cloze;

use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\Domain\Model\AbstractConfiguration;

/**
 * Class ClozeEditorConfiguration
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class ClozeEditorConfiguration extends AbstractConfiguration {
    
    /**
     * @var string
     */
    protected $cloze_text;
    
    /**
     * @var ClozeGapConfiguration[]
     */
    protected $gaps = [];
    
    public static function create(string $cloze_text, array $gaps) : ClozeEditorConfiguration {
        $config = new ClozeEditorConfiguration();
        $config->cloze_text = $cloze_text;
        $config->gaps = $gaps;
        return $config;
    }
    
    
    
    /**
     * @return string
     */
    public function getClozeText()
    {
        return $this->cloze_text;
    }

    /**
     * @return ClozeGapConfiguration[]
     */
    public function getGaps()
    {
        return $this->gaps;
    }

    public function equals(AbstractValueObject $other): bool
    {
        /** @var ClozeEditorConfiguration $other */
        return get_class($this) === get_class($other) &&
               $this->cloze_text === $other->cloze_text &&
               $this->gapsEquals($other->gaps);
    } 
    
    /**
     * @param array $gaps
     * @return bool
     */
    private function gapsEquals(?array $gaps) : bool
    {
        if (count($this->gaps) !== count($gaps)) {
            return false;
        }
        
        for ($i = 0; $i < count($gaps); $i += 1) {
            if(!$this->gaps[$i]->equals($gaps[$i])) {
                return false;
            }
        }
        
        return true;
    }
}