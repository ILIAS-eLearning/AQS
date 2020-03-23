<?php
declare(strict_types=1);

namespace srag\asq\Domain\Event;

use srag\CQRS\Aggregate\AbstractValueObject;
use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Event\AbstractIlContainerItemDomainEvent;
use srag\asq\Domain\Model\QuestionLegacyData;

/**
 * Class QuestionLegacyDataSetEvent
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class QuestionLegacyDataSetEvent extends AbstractIlContainerItemDomainEvent {

	public const NAME = 'QuestionLegacyDataSetEvent';

	/**
	 * @var QuestionLegacyData
	 */
	protected $legacy_data;


    /**
     * QuestionLegacyDataSetEvent constructor.
     *
     * @param DomainObjectId          $question_uuid
     * @param int                     $container_obj_id
     * @param int                     $initiating_user_id
     * @param QuestionLegacyData|null $legacy_data
     *
     * @throws \ilDateTimeException
     */
	public function __construct
	(
	    DomainObjectId $aggregate_id,
		int $container_obj_id,
		int $initiating_user_id,
	    int $question_int_id,
		QuestionLegacyData $legacy_data = null
	)
	{
	    parent::__construct($aggregate_id, $question_int_id, $container_obj_id, $initiating_user_id);
	    
		$this->legacy_data = $legacy_data;
	}

	/**
	 * @return string
	 *
	 * Add a Constant EVENT_NAME to your class: Name it: Classname
	 * e.g. 'QuestionCreatedEvent'
	 */
	public function getEventName(): string {
		return self::NAME;
	}

	/**
	 * @return QuestionLegacyData
	 */
	public function getLegacyData(): QuestionLegacyData {
		return $this->legacy_data;
	}

    /**
     * @return string
     */
	public function getEventBody(): string {
		return json_encode($this->legacy_data);
	}

	/**
	 * @param string $json_data
	 */
	public function restoreEventBody(string $json_data) : void {
		$this->legacy_data = AbstractValueObject::deserialize($json_data);
	}
}