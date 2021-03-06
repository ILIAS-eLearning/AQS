<?php
declare(strict_types=1);

namespace srag\asq\Questions\FileUpload\Scoring;

use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Domain\QuestionDto;
use srag\asq\Domain\Model\Scoring\AbstractScoring;
use srag\asq\Questions\FileUpload\Scoring\Data\FileUploadScoringConfiguration;

/**
 * Class FileUploadScoring
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class FileUploadScoring extends AbstractScoring
{
    /**
     * @var FileUploadScoringConfiguration
     */
    protected $configuration;

    /**
     * @param QuestionDto $question
     */
    public function __construct($question)
    {
        parent::__construct($question);

        $this->configuration = $question->getPlayConfiguration()->getScoringConfiguration();
    }

    /**
     * @param AbstractValueObject $answer
     * @return float
     */
    public function score(AbstractValueObject $answer) : float
    {
        $reached_points = 0;

        if ($this->configuration->isCompletedBySubmition()) {
            if ($answer->getFiles() !== null && count($answer->getFiles()) > 0) {
                $reached_points = $this->getMaxScore();
            }
        } else {
            throw new AsqException('Cant automatically score Fileupload');
        }

        return $reached_points;
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\Domain\Model\Scoring\AbstractScoring::calculateMaxScore()
     */
    protected function calculateMaxScore() : float
    {
        return $this->configuration->getPoints();
    }

    /**
     * @throws AsqException
     * @return AbstractValueObject
     */
    public function getBestAnswer() : AbstractValueObject
    {
        throw new AsqException(self::BEST_ANSWER_CREATION_IMPOSSIBLE_ERROR);
    }

    /**
     * @return bool
     */
    public function isComplete() : bool
    {
        return ! is_null($this->configuration->getPoints());
    }
}
