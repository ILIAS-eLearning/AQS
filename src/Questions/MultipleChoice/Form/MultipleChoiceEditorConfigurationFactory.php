<?php
declare(strict_types = 1);

namespace srag\asq\Questions\MultipleChoice\Form;

use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\UserInterface\Web\Form\AbstractObjectFactory;
use srag\asq\Questions\MultipleChoice\MultipleChoiceEditorConfiguration;
use ilCheckboxInputGUI;
use ilNumberInputGUI;
use ilSelectInputGUI;
use ilHiddenInputGUI;

/**
 * Class MultipleChoiceEditorConfigurationFactory

 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class MultipleChoiceEditorConfigurationFactory extends AbstractObjectFactory
{
    const VAR_MCE_SHUFFLE = 'shuffle';
    const VAR_MCE_MAX_ANSWERS = 'max_answers';
    const VAR_MCE_THUMB_SIZE = 'thumbsize';
    const VAR_MCE_IS_SINGLELINE = 'singleline';

    const STR_TRUE = "true";
    const STR_FALSE = "false";

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\IObjectFactory::getFormfields()
     */
    public function getFormfields(?AbstractValueObject $value): array
    {
        $fields = [];

        $shuffle = new ilCheckboxInputGUI($this->language->txt('asq_label_shuffle'), self::VAR_MCE_SHUFFLE);

        $shuffle->setValue(self::STR_TRUE);
        $fields[self::VAR_MCE_SHUFFLE] = $shuffle;

        $max_answers = new ilNumberInputGUI($this->language->txt('asq_label_max_answer'), self::VAR_MCE_MAX_ANSWERS);
        $max_answers->setInfo($this->language->txt('asq_description_max_answer'));
        $max_answers->setDecimals(0);
        $max_answers->setSize(2);
        $fields[self::VAR_MCE_MAX_ANSWERS] = $max_answers;

        $singleline = new ilSelectInputGUI($this->language->txt('asq_label_editor'), self::VAR_MCE_IS_SINGLELINE);

        $singleline->setOptions([
            self::STR_TRUE => $this->language->txt('asq_option_single_line'),
            self::STR_FALSE => $this->language->txt('asq_option_multi_line')
        ]);

        $fields[self::VAR_MCE_IS_SINGLELINE] = $singleline;

        if ($value === null || $value->isSingleLine()) {
            $thumb_size = new ilNumberInputGUI($this->language->txt('asq_label_thumb_size'), self::VAR_MCE_THUMB_SIZE);
            $thumb_size->setInfo($this->language->txt('asq_description_thumb_size'));
            $thumb_size->setSuffix($this->language->txt('asq_pixel'));
            $thumb_size->setMinValue(20);
            $thumb_size->setDecimals(0);
            $thumb_size->setSize(6);
            $fields[self::VAR_MCE_THUMB_SIZE] = $thumb_size;
        } else {
            $thumb_size = new ilHiddenInputGUI(self::VAR_MCE_THUMB_SIZE);
            $fields[self::VAR_MCE_THUMB_SIZE] = $thumb_size;
        }

        if ($value !== null) {
            $shuffle->setChecked($value->isShuffleAnswers());
            $max_answers->setValue($value->getMaxAnswers());
            $thumb_size->setValue($value->getThumbnailSize());
            $singleline->setValue($value->isSingleLine() ? self::STR_TRUE : self::STR_FALSE);
        } else {
            $shuffle->setChecked(true);
            $max_answers->setValue(1);
        }

        return $fields;
    }

    /**
     * @return MultipleChoiceEditorConfiguration
     */
    public function readObjectFromPost(): AbstractValueObject
    {
        return MultipleChoiceEditorConfiguration::create(
            $_POST[self::VAR_MCE_SHUFFLE] === self::STR_TRUE,
            $this->readInt(self::VAR_MCE_MAX_ANSWERS),
            $this->readInt(self::VAR_MCE_THUMB_SIZE),
            $_POST[self::VAR_MCE_IS_SINGLELINE] === self::STR_TRUE);
    }

    /**
     * @return MultipleChoiceEditorConfiguration
     */
    public function getDefaultValue(): AbstractValueObject
    {
        return MultipleChoiceEditorConfiguration::create();
    }
}