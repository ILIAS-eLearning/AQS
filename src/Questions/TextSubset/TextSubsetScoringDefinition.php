<?php
declare(strict_types=1);

namespace srag\asq\Questions\TextSubset;

use stdClass;
use srag\asq\Domain\Model\QuestionPlayConfiguration;
use srag\asq\Domain\Model\Answer\Option\AnswerDefinition;
use srag\asq\UserInterface\Web\AsqHtmlPurifier;
use srag\asq\UserInterface\Web\Fields\AsqTableInputFieldDefinition;

/**
 * Class TextSubsetScoringDefinition
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class TextSubsetScoringDefinition extends AnswerDefinition {
    
    const VAR_TSSD_POINTS = 'tssd_points';
    const VAR_TSSD_TEXT = 'tsdd_text' ;
    
    /**
     * @var int
     */
    protected $points;
    /**
     * @var string
     */
    protected $text;
    
    /**
     * TextSubsetScoringDefinition constructor.
     *
     * @param int $points
     */
    public function __construct(int $points, ?string $text)
    {
        $this->points = $points;
        $this->text = $text;
    }  
    
    /**
     * @return int
     */
    public function getPoints(): int {
        return $this->points;
    }
    
    /**
     * @return string
     */
    public function getText(): string {
        return $this->text;
    }
    
    public static function getFields(QuestionPlayConfiguration $play): array {
        global $DIC;
        
        $fields = [];
        $fields[] = new AsqTableInputFieldDefinition(
            $DIC->language()->txt('asq_label_answer_text'),
            AsqTableInputFieldDefinition::TYPE_TEXT,
            self::VAR_TSSD_TEXT
            );
        
        $fields[] = new AsqTableInputFieldDefinition(
            $DIC->language()->txt('asq_label_points'),
            AsqTableInputFieldDefinition::TYPE_NUMBER,
            self::VAR_TSSD_POINTS
            );
        
        return $fields;
    }
    
    public static function getValueFromPost(string $index) {
        return new TextSubsetScoringDefinition(intval($_POST[self::getPostKey($index, self::VAR_TSSD_POINTS)]),
            AsqHtmlPurifier::getInstance()->purify($_POST[self::getPostKey($index, self::VAR_TSSD_TEXT)]));
    }
    
    public function getValues(): array {
        return [self::VAR_TSSD_POINTS => $this->points,
                self::VAR_TSSD_TEXT => $this->text
        ];
    }
    
    
    public static function deserialize(stdClass $data) {
        return new TextSubsetScoringDefinition(
            $data->points, $data->text);
    }
}