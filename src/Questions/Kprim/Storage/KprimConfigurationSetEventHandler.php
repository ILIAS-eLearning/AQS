<?php
declare(strict_types=1);

namespace srag\asq\Questions\Kprim\Storage;

use ilDateTime;
use srag\CQRS\Event\DomainEvent;
use srag\asq\Domain\Event\QuestionPlayConfigurationSetEvent;
use srag\asq\Domain\Model\Configuration\QuestionPlayConfiguration;
use srag\asq\Infrastructure\Persistence\RelationalEventStore\AbstractEventStorageHandler;
use srag\asq\Questions\Kprim\Editor\Data\KprimChoiceEditorConfiguration;
use srag\asq\Questions\Kprim\Scoring\Data\KprimChoiceScoringConfiguration;

/**
 * Class KprimConfigurationSetEventHandler
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class KprimConfigurationSetEventHandler extends AbstractEventStorageHandler
{
    /**
     * @param DomainEvent $event
     */
    public function handleEvent(DomainEvent $event, int $event_id) : void
    {
        /** @var $editor_config KprimChoiceEditorConfiguration */
        $editor_config = $event->getPlayConfiguration()->getEditorConfiguration();
        /** @var $scoring_config KprimChoiceScoringConfiguration */
        $scoring_config = $event->getPlayConfiguration()->getScoringConfiguration();

        $id = intval($this->db->nextId(SetupKprim::TABLENAME_KPRIM_CONFIGURATION));
        $this->db->insert(SetupKprim::TABLENAME_KPRIM_CONFIGURATION, [
            'config_id' => ['integer', $id],
            'event_id' => ['integer', $event_id],
            'shuffle' => ['integer', $editor_config->isShuffleAnswers()],
            'thumbnail_size' => ['integer', $editor_config->getThumbnailSize()],
            'label_true' => ['text', $editor_config->getLabelTrue()],
            'label_false' => ['text', $editor_config->getLabelFalse()],
            'points' => ['float', $scoring_config->getPoints()],
            'half_points_at' => ['integer', $scoring_config->getHalfPointsAt()]
        ]);
    }

    /**
     * @param array $data
     * @return DomainEvent
     */
    public function loadEvent(array $data) : DomainEvent
    {
        $res = $this->db->query(
            sprintf(
                'select * from ' . SetupKprim::TABLENAME_KPRIM_CONFIGURATION .' c
                 where c.event_id = %s',
                $this->db->quote($data['id'], 'int')
                )
            );

        $row = $this->db->fetchAssoc($res);

        return new QuestionPlayConfigurationSetEvent(
            $this->factory->fromString($data['question_id']),
            new ilDateTime($data['occurred_on'], IL_CAL_UNIX),
            intval($data['initiating_user_id']),
            new QuestionPlayConfiguration(
                new KprimChoiceEditorConfiguration(
                        boolval($row['shuffle']),
                        intval($row['thumbnail_size']),
                        $row['label_true'],
                        $row['label_false']
                    ),
                new KprimChoiceScoringConfiguration(
                        floatval($row['points']),
                        intval($row['half_points_at'])
                    )
                )
            );
    }
}