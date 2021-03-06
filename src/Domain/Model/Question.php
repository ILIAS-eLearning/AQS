<?php
declare(strict_types=1);

namespace srag\asq\Domain\Model;

use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use srag\CQRS\Aggregate\AbstractAggregateRoot;
use srag\CQRS\Aggregate\IsRevisable;
use srag\CQRS\Aggregate\RevisionId;
use srag\CQRS\Event\DomainEvent;
use srag\CQRS\Event\Standard\AggregateCreatedEvent;
use srag\CQRS\Event\Standard\AggregateRevisionCreatedEvent;
use srag\asq\Domain\Event\QuestionAnswerOptionsSetEvent;
use srag\asq\Domain\Event\QuestionDataSetEvent;
use srag\asq\Domain\Event\QuestionFeedbackSetEvent;
use srag\asq\Domain\Event\QuestionHintsSetEvent;
use srag\asq\Domain\Event\QuestionPlayConfigurationSetEvent;
use srag\asq\Domain\Model\Configuration\QuestionPlayConfiguration;
use srag\asq\Domain\Model\Feedback\Feedback;
use srag\asq\Domain\Model\Hint\QuestionHints;
use srag\asq\Infrastructure\Persistence\QuestionType;
use srag\asq\Domain\Model\Answer\Option\AnswerOption;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class Question
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class Question extends AbstractAggregateRoot implements IsRevisable
{
    const VAR_TYPE = 'question_type';

    /**
     * @var string
     */
    private $question_type;
    /**
     * @var RevisionId
     */
    private $revision_id;
    /**
     * @var int
     */
    private $creator_id;
    /**
     * @var QuestionData
     */
    private $data;
    /**
     * @var QuestionPlayConfiguration
     */
    private $play_configuration;
    /**
     * @var AnswerOption[]
     */
    private $answer_options;
    /**
     * @var QuestionHints
     */
    private $hints;
    /**
     * @var Feedback
     */
    private $feedback;

    /**
     * @var bool
     */
    private $has_unrevisioned_changes;

    /**
     * @param Uuid $question_uuid
     * @param int $initiating_user_id
     * @param QuestionType $question_type
     * @return Question
     */
    public static function createNewQuestion(
        Uuid $question_uuid,
        int $initiating_user_id,
        QuestionType $question_type
    ) : Question {
        $question = new Question();
        $question->ExecuteEvent(
            new AggregateCreatedEvent(
                $question_uuid,
                new ilDateTime(time(), IL_CAL_UNIX),
                $initiating_user_id,
                [self::VAR_TYPE => $question_type->getTitleKey()]
            )
        );

        return $question;
    }


    /**
     * @param DomainEvent $event
     */
    protected function applyEvent(DomainEvent $event)
    {
        $this->has_unrevisioned_changes = get_class($event) !== AggregateRevisionCreatedEvent::class;

        parent::applyEvent($event);
    }

    /**
     * @param AggregateCreatedEvent $event
     */
    protected function applyAggregateCreatedEvent(DomainEvent $event)
    {
        parent::applyAggregateCreatedEvent($event);
        $this->creator_id = $event->getInitiatingUserId();
        $this->question_type = $event->getAdditionalData()[self::VAR_TYPE];
    }


    /**
     * @param QuestionDataSetEvent $event
     */
    protected function applyQuestionDataSetEvent(QuestionDataSetEvent $event)
    {
        $this->data = $event->getData();
    }


    /**
     * @param QuestionPlayConfigurationSetEvent $event
     */
    protected function applyQuestionPlayConfigurationSetEvent(QuestionPlayConfigurationSetEvent $event)
    {
        $this->play_configuration = $event->getPlayConfiguration();
    }


    /**
     * @param AggregateRevisionCreatedEvent $event
     */
    protected function applyAggregateRevisionCreatedEvent(AggregateRevisionCreatedEvent $event)
    {
        $this->revision_id = $event->getRevisionId();
    }


    /**
     * @param QuestionAnswerOptionsSetEvent $event
     */
    protected function applyQuestionAnswerOptionsSetEvent(QuestionAnswerOptionsSetEvent $event)
    {
        $this->answer_options = $event->getAnswerOptions();
    }


    /**
     * @param QuestionHintsSetEvent $event
     */
    protected function applyQuestionHintsSetEvent(QuestionHintsSetEvent $event)
    {
        $this->hints = $event->getHints();
    }

    /**
     * @param QuestionFeedbackSetEvent $event
     */
    protected function applyQuestionFeedbackSetEvent(QuestionFeedbackSetEvent $event)
    {
        $feedback = $event->getFeedback();
        $this->feedback = $feedback;
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return $this->question_type;
    }

    /**
     * @return QuestionData
     */
    public function getData() : ?QuestionData
    {
        return $this->data;
    }

    /**
     * @param QuestionData $data
     * @param int          $container_obj_id
     * @param int          $creator_id
     */
    public function setData(?QuestionData $data, int $creator_id)
    {
        if (!QuestionData::isNullableEqual($data, $this->getData())) {
            $this->ExecuteEvent(new QuestionDataSetEvent($this->getAggregateId(), new ilDateTime(time(), IL_CAL_UNIX), $creator_id, $data));
        }
    }

    /**
     *
     * @return QuestionPlayConfiguration
     */
    public function getPlayConfiguration() : ?QuestionPlayConfiguration
    {
        return $this->play_configuration;
    }


    /**
     * @param QuestionPlayConfiguration $play_configuration
     * @param int                       $creator_id
     */
    public function setPlayConfiguration(
        ?QuestionPlayConfiguration $play_configuration,
        int $creator_id
    ) : void {
        if (!QuestionPlayConfiguration::isNullableEqual($play_configuration, $this->getPlayConfiguration())) {
            $this->ExecuteEvent(new QuestionPlayConfigurationSetEvent(
                $this->getAggregateId(),
                new ilDateTime(time(), IL_CAL_UNIX),
                $creator_id,
                $play_configuration
            ));
        }
    }

    /**
     * @return ?AnswerOption[]
     */
    public function getAnswerOptions() : ?array
    {
        return $this->answer_options;
    }

    /**
     *
     * @param AnswerOption[] $options
     * @param int $creator_id
     */
    public function setAnswerOptions(?array $options, int $creator_id)
    {
        if (AbstractValueObject::isNullableArrayEqual($options, $this->answer_options)) {
            return;
        }

        $this->ExecuteEvent(new QuestionAnswerOptionsSetEvent(
            $this->getAggregateId(),
            new ilDateTime(time(), IL_CAL_UNIX),
            $creator_id,
            $options
        ));
    }


    /**
     * @return QuestionHints
     */
    public function getHints() : ?QuestionHints
    {
        return $this->hints;
    }


    /**
     * @param QuestionHints $hints
     * @param int           $creator_id
     */
    public function setHints(?QuestionHints $hints, int $creator_id = self::SYSTEM_USER_ID)
    {
        if (!QuestionHints::isNullableEqual($hints, $this->getHints())) {
            $this->ExecuteEvent(new QuestionHintsSetEvent(
                $this->getAggregateId(),
                new ilDateTime(time(), IL_CAL_UNIX),
                $creator_id,
                $hints
            ));
        }
    }

    /**
     * @return Feedback
     */
    public function getFeedback() : ?Feedback
    {
        return $this->feedback;
    }


    /**
     * @param Feedback $feedback
     * @param int $creator_id
     */
    public function setFeedback(
        ?Feedback $feedback,
        int $creator_id
    ) : void {
        if (!Feedback::isNullableEqual($feedback, $this->getFeedback())) {
            $this->ExecuteEvent(new QuestionFeedbackSetEvent(
                $this->getAggregateId(),
                new ilDateTime(time(), IL_CAL_UNIX),
                $creator_id,
                $feedback
            ));
        }
    }

    /**
     * @return int
     */
    public function getCreatorId() : int
    {
        return $this->creator_id;
    }

    /**
     * @param int $creator_id
     */
    public function setCreatorId(int $creator_id) : void
    {
        $this->creator_id = $creator_id;
    }

    /**
     * @return bool
     */
    public function hasUnrevisionedChanges() : bool
    {
        return $this->has_unrevisioned_changes;
    }

    /**
     * @return RevisionId revision id of object
     */
    public function getRevisionId() : ?RevisionId
    {
        return $this->revision_id;
    }

    /**
     * @param RevisionId $id
     *
     * @return mixed|void
     */
    public function setRevisionId(RevisionId $id, int $user_id)
    {
        $this->ExecuteEvent(new AggregateRevisionCreatedEvent(
            $this->getAggregateId(),
            new ilDateTime(time(), IL_CAL_UNIX),
            $user_id,
            $id
        ));
    }
}
