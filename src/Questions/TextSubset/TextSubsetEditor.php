<?php
declare(strict_types = 1);
namespace srag\asq\Questions\TextSubset;

use ilTemplate;
use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\Domain\QuestionDto;
use srag\asq\Domain\Model\Answer\Option\EmptyDefinition;
use srag\asq\UserInterface\Web\AsqHtmlPurifier;
use srag\asq\UserInterface\Web\PathHelper;
use srag\asq\UserInterface\Web\Component\Editor\AbstractEditor;

/**
 * Class TextSubsetEditor
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 * @package srag/asq
 * @author Adrian Lüthi <al@studer-raimann.ch>
 */
class TextSubsetEditor extends AbstractEditor
{
    use PathHelper;

    /**
     * @var TextSubsetEditorConfiguration
     */
    private $configuration;

    /**
     * @param QuestionDto $question
     */
    public function __construct(QuestionDto $question)
    {
        $this->configuration = $question->getPlayConfiguration()->getEditorConfiguration();

        parent::__construct($question);
    }

    /**
     * @return string
     */
    public function generateHtml() : string
    {
        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'templates/default/tpl.TextSubsetEditor.html', true, true);

        for ($i = 1; $i <= $this->configuration->getNumberOfRequestedAnswers(); $i ++) {
            $tpl->setCurrentBlock('textsubset_row');
            $tpl->setVariable('COUNTER', $i);
            $tpl->setVariable('TEXTFIELD_ID', $this->getPostValue($i));
            $tpl->setVariable('TEXTFIELD_SIZE', $this->calculateSize());

            if (! is_null($this->answer) && ! is_null($this->answer->getAnswers()[$i])) {
                $tpl->setVariable('TEXTFIELD_VALUE', 'value="' . $this->answer->getAnswers()[$i] . '"');
            }

            $tpl->parseCurrentBlock();
        }

        return $tpl->get();
    }

    /**
     * @param int $i
     * @return string
     */
    private function getPostValue(int $i) : string
    {
        return $i . $this->question->getId();
    }

    /**
     * @return int
     */
    private function calculateSize() : int
    {
        $max = 1;
        foreach ($this->question->getAnswerOptions()->getOptions() as $option) {
            max($max, strlen($option->getScoringDefinition()->getText()));
        }

        return $max += 10 - ($max % 10);
    }

    /**
     * @return ?AbstractValueObject
     */
    public function readAnswer() : ?AbstractValueObject
    {
        if (! array_key_exists($this->getPostValue(1), $_POST)) {
            return null;
        }

        $answer = [];

        $purifier = new AsqHtmlPurifier();

        for ($i = 1; $i <= $this->configuration->getNumberOfRequestedAnswers(); $i ++) {
            $answer[$i] = $purifier->purify($_POST[$this->getPostValue($i)]);
        }

        return TextSubsetAnswer::create($answer);
    }

    /**
     * @return string
     */
    static function getDisplayDefinitionClass() : string
    {
        return EmptyDefinition::class;
    }

    /**
     * @return bool
     */
    public function isComplete() : bool
    {
        if (empty($this->configuration->getNumberOfRequestedAnswers())) {
            return false;
        }

        return true;
    }
}