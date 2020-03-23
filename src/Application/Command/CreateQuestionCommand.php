<?php
declare(strict_types=1);

namespace srag\asq\Application\Command;

use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Command\AbstractCommand;
use srag\CQRS\Command\CommandContract;

/**
 * Class CreateQuestionCommand
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * 
 */
class CreateQuestionCommand extends AbstractCommand implements CommandContract {

	/**
	 * @var  DomainObjectId
	 */
	protected $question_uuid;
	/**
	 * @var ?int;
	 */
	protected $container_id;
	/**
	 * @var int
	 */
    protected $question_type_id;
	/**
	 * @var ?string
	 */
	protected $content_editing_mode;
	
    /**
     * @param DomainObjectId $question_uuid
     * @param int $initiating_user_id
     * @param int $container_id
     * @param int $answer_type_id
     */
	public function __construct(
		DomainObjectId $question_uuid,
	    int $question_type_id,
		int $initiating_user_id,
		?int $container_id = null,
	    ?string $content_editing_mode = null
	) {
		parent::__construct($initiating_user_id);
		$this->question_uuid = $question_uuid;
		$this->container_id = $container_id;
		$this->question_type_id = $question_type_id;
		$this->content_editing_mode = $content_editing_mode;
	}

	/**
	 * @return DomainObjectId
	 */
	public function getQuestionUuid(): DomainObjectId {
		return $this->question_uuid;
	}

    /**
     * @return int
     */
	public function getQuestionContainerId(): ?int {
		return $this->container_id;
	}

	/**
	 * @return int
	 */
	public function getQuestionType(): int {
	    return $this->question_type_id;
	}
	
	/**
     * @return string|null
	 */
	public function getContentEditingMode(): ?string {
	    return $this->content_editing_mode;
	}
}