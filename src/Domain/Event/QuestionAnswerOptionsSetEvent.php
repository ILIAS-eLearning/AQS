<?php
declare(strict_types=1);

namespace srag\asq\Domain\Event;

use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Event\AbstractDomainEvent;
use srag\asq\Domain\Model\Answer\Option\AnswerOptions;
use ilDateTime;

/**
 * Class QuestionAnswerOptionsSetEvent
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class QuestionAnswerOptionsSetEvent extends AbstractDomainEvent {
	/**
	 * @var AnswerOptions
	 */
	protected $answer_options;

    /**
     * @param DomainObjectId $aggregate_id
     * @param ilDateTime $occured_on
     * @param int $initiating_user_id
     * @param int $question_int_id
     * @param AnswerOptions $options
     */
	public function __construct(DomainObjectId $aggregate_id, 
	                            ilDateTime $occured_on, 
	                            int $initiating_user_id, 
	                            AnswerOptions $options = null)
	{
	    parent::__construct($aggregate_id, $occured_on, $initiating_user_id);
	    
		$this->answer_options = $options;
	}

	/**
	 * @return AnswerOptions
	 */
	public function getAnswerOptions(): AnswerOptions {
		return $this->answer_options;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEventBody(): string {
		return json_encode($this->answer_options);
	}

	/**
	 * @param string $json_data
	 */
	public function restoreEventBody(string $json_data) : void {
        $this->answer_options = AnswerOptions::deserialize($json_data);
	}
	
	/**
	 * @return int
	 */
	public static function getEventVersion(): int
	{
	    // initial version 1
	    return 1;
	}
}