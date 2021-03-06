<?php
declare(strict_types=1);

namespace srag\asq\Domain\Model;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class QuestionData
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class QuestionData extends AbstractValueObject
{
    const LIFECYCLE_DRAFT = 1;
    const LIFECYCLE_TO_BE_REVIEWED = 2;
    const LIFECYCLE_REJECTED = 3;
    const LIFECYCLE_FINAL = 4;
    const LIFECYCLE_SHARABLE = 5;
    const LIFECYCLE_OUTDATED = 6;

    /**
    * @var ?string
    */
    protected $title;
    /**
     * @var ?string
     */
    protected $description;
    /**
     * @var ?int
     */
    protected $lifecycle = self::LIFECYCLE_DRAFT;
    /**
     * @var ?string
     */
    protected $question_text;
    /**
     * @var ?string
     */
    protected $author;
    /**
     * @var ?int
     */
    protected $working_time = 0;

    /**
     * @param string $title
     * @param string $text
     * @param string $author
     * @param string $description
     * @param int $working_time
     * @param int $lifecycle
     */
    public function __construct(
        ?string $title = null,
        ?string $text = null,
        ?string $author = null,
        ?string $description = null,
        ?int $working_time = null,
        ?int $lifecycle = self::LIFECYCLE_DRAFT
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->question_text = $text;
        $this->author = $author;
        $this->working_time = $working_time;
        $this->lifecycle = $lifecycle;
    }

    /**
     * @return string
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription() : ?string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getLifecycle() : ?int
    {
        return $this->lifecycle;
    }

    /**
     * @return string
     */
    public function getQuestionText() : ?string
    {
        return $this->question_text;
    }

    /**
     * @return string
     */
    public function getAuthor() : ?string
    {
        return $this->author;
    }

    /**
     * @return int
     */
    public function getWorkingTime() : ?int
    {
        return $this->working_time;
    }

    public function isComplete() : bool
    {
        return !empty($this->title) &&
               !empty($this->working_time) &&
               !empty($this->author) &&
               !empty($this->question_text) &&
               !empty($this->lifecycle);
    }

    /**
     * @param AbstractValueObject $other
     *
     * @return bool
     */
    public function equals(AbstractValueObject $other) : bool
    {
        /** @var QuestionData $other */
        return get_class($this) === get_class($other) &&
               $this->getAuthor() === $other->getAuthor() &&
               $this->getDescription() === $other->getDescription() &&
               $this->getLifecycle() === $other->getLifecycle() &&
               $this->getQuestionText() === $other->getQuestionText() &&
               $this->getTitle() === $other->getTitle() &&
               $this->getWorkingTime() === $other->getWorkingTime();
    }
}
