<?php
declare(strict_types=1);

namespace srag\asq\Questions\TextSubset;

use srag\asq\Domain\Model\AbstractConfiguration;
use srag\asq\Domain\Model\Question;
use srag\asq\Domain\Model\Answer\Answer;
use srag\asq\Domain\Model\Answer\Option\AnswerOptions;
use srag\asq\Domain\Model\Scoring\AbstractScoring;
use srag\asq\Domain\Model\Scoring\TextScoring;
use srag\asq\Application\Exception\AsqException;

/**
 * Class TextSubsetScoring
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class TextSubsetScoring extends AbstractScoring
{

    /**
     * @var Answer
     */
    protected $answer;
    
    const VAR_TEXT_MATCHING = 'tss_text_matching';
    
    /**
     * 
     * @param Answer $answer
     * @throws AsqException
     * @return float
     */
    public function score(Answer $answer) : float
    {
        $this->answer = $answer;

        /** @var TextSubsetScoringConfiguration $scoring_conf */
        $scoring_conf = $this->question->getPlayConfiguration()->getScoringConfiguration();
        
        switch ($scoring_conf->getTextMatching()) {
            case TextScoring::TM_CASE_INSENSITIVE:
                return $this->caseInsensitiveScoring();
            case TextScoring::TM_CASE_SENSITIVE:
                return $this->caseSensitiveScoring();
            case TextScoring::TM_LEVENSHTEIN_1:
                return $this->levenshteinScoring(1);
            case TextScoring::TM_LEVENSHTEIN_2:
                return $this->levenshteinScoring(2);
            case TextScoring::TM_LEVENSHTEIN_3:
                return $this->levenshteinScoring(3);
            case TextScoring::TM_LEVENSHTEIN_4:
                return $this->levenshteinScoring(4);
            case TextScoring::TM_LEVENSHTEIN_5:
                return $this->levenshteinScoring(5);
        }
        
        throw new AsqException("Unknown Test Subset Scoring Method found");
    }
    
    public function getBestAnswer(): Answer
    {
        $answers = [];
        
        foreach ($this->question->getAnswerOptions()->getOptions() as $option) {
            $answers[] = $option->getScoringDefinition()->getText();
        }
        
        return TextSubsetAnswer::create($answers);
    }

    protected function calculateMaxScore()
    {
        $amount = $this->question->getPlayConfiguration()->getEditorConfiguration()->getNumberOfRequestedAnswers();
        
        if(empty($amount)) {
            return 0;
        }
        
        $points = array_map(function($option) {
            return $option->getScoringDefinition()->getPoints();
        }, $this->question->getAnswerOptions()->getOptions());
            
        rsort($points);
        
        $this->max_score = array_sum(array_slice($points, 0, $amount));
    }
    
    /**
     * @param array $answer_arr
     * @return int
     */
    private function caseInsensitiveScoring() : float {
        $reached_points = 0;
        
        foreach ($this->getAnswers() as $result) {
            foreach ($this->question->getAnswerOptions()->getOptions() as $correct) {
                if (strtoupper($correct->getScoringDefinition()->getText()) === strtoupper($result)) {
                    $reached_points += $correct->getScoringDefinition()->getPoints();
                    break;
                }
            }
        }

        return $reached_points;
    }
    
    /**
     * @param array $answer_arr
     * @return int
     */
    private function caseSensitiveScoring() : float {
        $reached_points = 0;
 
        foreach ($this->getAnswers() as $result) {
            foreach ($this->question->getAnswerOptions()->getOptions() as $correct) {
                if ($correct->getScoringDefinition()->getText() === $result) {
                    $reached_points += $correct->getScoringDefinition()->getPoints();
                    break;
                }
            }
        }

        return $reached_points;
    }
    
    /**
     * @param array $answer_arr
     * @param int $distance
     * @return int
     */
    private function levenshteinScoring(int $distance) : float {
        $reached_points = 0;

        foreach ($this->getAnswers() as $result) {
            foreach ($this->question->getAnswerOptions()->getOptions() as $correct) {
                if (levenshtein($correct->getScoringDefinition()->getText(), $result) <= $distance) {
                    $reached_points += $correct->getScoringDefinition()->getPoints();
                    break;
                }
            }
        }

        return $reached_points;
    }
    
    private function getAnswers() : array {
        return array_unique($this->answer->getAnswers());
    }
    
    /**
     * @param AbstractConfiguration|null $config
     *
     * @return array|null
     */
    public static function generateFields(?AbstractConfiguration $config, AnswerOptions $options = null): ?array {
        /** @var TextSubsetScoringConfiguration $config */

        $fields = [];

        $text_matching = TextScoring::getScoringTypeSelectionField(self::VAR_TEXT_MATCHING);
        $fields[self::VAR_TEXT_MATCHING] = $text_matching;
        
        if ($config !== null) {
            $text_matching->setValue($config->getTextMatching());
        }
        
        return $fields;
    }
    
    public static function readConfig()
    {
        return TextSubsetScoringConfiguration::create(
            intval($_POST[self::VAR_TEXT_MATCHING]));
    }
    
    public static function isComplete(Question $question): bool
    {
        /** @var TextSubsetScoringConfiguration $config */
        $config = $question->getPlayConfiguration()->getScoringConfiguration();
        
        if (empty($config->getTextMatching())) {
            return false;
        }
        
        foreach ($question->getAnswerOptions()->getOptions() as $option) {
            /** @var TextSubsetScoringDefinition $option_config */
            $option_config = $option->getScoringDefinition();
            
            if (empty($option_config->getText()) ||
                empty($option_config->getPoints()))
            {
                return false;
            }
        }
        
        return true;
    }
}