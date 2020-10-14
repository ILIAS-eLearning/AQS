<?php
declare(strict_types=1);

namespace srag\asq\Infrastructure\Persistence\RelationalEventStore\GenericHandlers;

use srag\CQRS\Event\DomainEvent;
use srag\asq\Infrastructure\Persistence\RelationalEventStore\IEventStorageHandler;
use srag\CQRS\Event\Standard\AggregateCreatedEvent;
use ILIAS\Data\UUID\Factory;
use ilDateTime;

/**
 * Class AggregateCreatedEventHandler
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class AggregateCreatedEventHandler implements IEventStorageHandler
{
    /**
     * @param DomainEvent $event
     */
    public function handleEvent(DomainEvent $event, int $event_id) : void
    {
        //nothing to do
    }

    /**
     * @param array $data
     * @return DomainEvent
     */
    public function loadEvent(array $data) : DomainEvent
    {
        $factory = new Factory();

        return new AggregateCreatedEvent(
            $factory->fromString($data['question_id']),
            new ilDateTime($data['occurred_on'], IL_CAL_UNIX),
            $data['initiating_user_id']);
    }
}