<?php
declare(strict_types=1);

namespace srag\asq\Questions\Essay;

use srag\asq\Domain\QuestionDto;
use srag\asq\Domain\Model\QuestionPlayConfiguration;
use srag\asq\Domain\Model\Answer\Option\AnswerOption;
use srag\asq\Domain\Model\Answer\Option\AnswerOptions;
use srag\asq\UserInterface\Web\AsqHtmlPurifier;
use srag\asq\UserInterface\Web\Component\Editor\EmptyDisplayDefinition;
use srag\asq\UserInterface\Web\Form\QuestionFormGUI;

/**
 * Class EssayQuestionGUI
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class EssayQuestionGUI extends QuestionFormGUI {
    protected function createDefaultPlayConfiguration(): QuestionPlayConfiguration
    {
        return QuestionPlayConfiguration::create(
            EssayEditorConfiguration::create(),
            EssayScoringConfiguration::create());
    }
    
    protected function readPlayConfiguration(): QuestionPlayConfiguration
    {
        return QuestionPlayConfiguration::create(
            EssayEditor::readConfig(),
            EssayScoring::readConfig());
    }
    
    protected function readAnswerOptions(QuestionDto $question) : AnswerOptions {
        $selected = intval($_POST[EssayScoring::VAR_SCORING_MODE]);
        $options = new AnswerOptions();
        
        if ($selected !== EssayScoring::SCORING_MANUAL) {
            if ($selected === EssayScoring::SCORING_AUTOMATIC_ALL) {
                $prefix = EssayScoring::VAR_ANSWERS_ALL;
            }
            else if ($selected === EssayScoring::SCORING_AUTOMATIC_ANY) {
                $prefix = EssayScoring::VAR_ANSWERS_ANY;
            }
            else if ($selected === EssayScoring::SCORING_AUTOMATIC_ONE) {
                $prefix = EssayScoring::VAR_ANSWERS_ONE;
            }
            
            $i = 1; 
            
            while (array_key_exists($this->getPostKey($i, $prefix, EssayScoringDefinition::VAR_TEXT), $_POST)) {
                $options->addOption(new AnswerOption(
                        strval($i),
                        new EmptyDisplayDefinition(),
                        new EssayScoringDefinition(
                            AsqHtmlPurifier::getInstance()->purify($_POST[$this->getPostKey($i, $prefix, EssayScoringDefinition::VAR_TEXT)]),
                            array_key_exists($this->getPostKey($i, $prefix, EssayScoringDefinition::VAR_POINTS), $_POST) ? 
                                intval($_POST[$this->getPostKey($i, $prefix, EssayScoringDefinition::VAR_POINTS)]) : null)));
                $i += 1;
            }
        }
    
        return $options;
    }
    
    private function getPostKey($i, $prefix, $suffix) {
        return sprintf('%s_%s_%s', $i, $prefix, $suffix);
    }
    
    protected function initiatePlayConfiguration(?QuestionPlayConfiguration $play): void
    {
        foreach (EssayEditor::generateFields($play->getEditorConfiguration()) as $field) {
            $this->addItem($field);
        }
        
        foreach (EssayScoring::generateFields(
                     $play->getScoringConfiguration(), 
                     $this->initial_question->getAnswerOptions()) as $field) {
            $this->addItem($field);
        }
    }
}