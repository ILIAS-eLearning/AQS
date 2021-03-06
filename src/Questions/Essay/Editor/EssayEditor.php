<?php
declare(strict_types=1);

namespace srag\asq\Questions\Essay\Editor;

use ILIAS\DI\UIServices;
use ilTemplate;
use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\Domain\QuestionDto;
use srag\asq\Infrastructure\Helpers\PathHelper;
use srag\asq\Questions\Essay\EssayAnswer;
use srag\asq\Questions\Essay\Editor\Data\EssayEditorConfiguration;
use srag\asq\UserInterface\Web\PostAccess;
use srag\asq\UserInterface\Web\Component\Editor\AbstractEditor;

/**
 * Class EssayEditor
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class EssayEditor extends AbstractEditor
{
    use PostAccess;
    use PathHelper;

    /**
     * @var EssayEditorConfiguration
     */
    private $configuration;

    /**
     * @var UIServices
     */
    private $ui;

    /**
     * @var ilLanguage;
     */
    private $language;

    /**
     * @param QuestionDto $question
     */
    public function __construct(QuestionDto $question)
    {
        global $DIC;

        $this->configuration = $question->getPlayConfiguration()->getEditorConfiguration();
        $this->ui = $DIC->ui();
        $this->ui->mainTemplate()->addCss($this->getBasePath(__DIR__) . 'css/asq.css');
        $this->language = $DIC->language();

        parent::__construct($question);
    }

    /**
     * @return string
     */
    public function generateHtml() : string
    {
        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'templates/default/tpl.EssayEditor.html', true, true);

        $tpl->setVariable('ESSAY', is_null($this->answer) ? '' : $this->answer->getText());
        $tpl->setVariable('POST_VAR', $this->question->getId()->toString());

        if (!empty($this->configuration->getMaxLength())) {
            $tpl->setCurrentBlock('maximum_char_hint');
            $tpl->setVariable('MAXIMUM_CHAR_HINT', $this->language->txt('asq_max_characters'));
            $tpl->setVariable('MAX_LENGTH', $this->configuration->getMaxLength());
            $tpl->setVariable('ERROR_MESSAGE', $this->language->txt('asq_error_too_long'));
            $tpl->parseCurrentBlock();

            $tpl->setCurrentBlock('maxchars_counter');
            $tpl->setVariable('CHARACTERS', $this->language->txt('asq_char_count'));
            $tpl->parseCurrentBlock();
        }

        // TODO wordcount??
        if (false) {
            $tpl->setCurrentBlock('maxchars_counter');
            $tpl->setVariable('CHARACTERS', $this->language->txt('asq_char_count'));
            $tpl->parseCurrentBlock();
        }

        $this->ui->mainTemplate()->addJavaScript($this->getBasePath(__DIR__) . 'src/Questions/Essay/Editor/EssayEditor.js');

        return $tpl->get();
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\Domain\Definitions\IAsqQuestionEditor::readAnswer()
     */
    public function readAnswer() : AbstractValueObject
    {
        return new EssayAnswer($this->getPostValue($this->question->getId()->toString()));
    }

    /**
     * @return bool
     */
    public function isComplete() : bool
    {
        // no necessary values
        return true;
    }
}
