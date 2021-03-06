<?php
declare(strict_types=1);

namespace srag\asq\Questions\Formula\Editor;

use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\Domain\QuestionDto;
use srag\asq\Questions\Formula\FormulaAnswer;
use srag\asq\Questions\Formula\Scoring\Data\FormulaScoringConfiguration;
use srag\asq\Questions\Formula\Scoring\Data\FormulaScoringVariable;
use srag\asq\UserInterface\Web\PostAccess;
use srag\asq\UserInterface\Web\Component\Editor\AbstractEditor;

/**
 * Class FormulaEditor
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class FormulaEditor extends AbstractEditor
{
    use PostAccess;

    const VAR_UNIT = 'fe_unit';

    /**
     * @var FormulaScoringConfiguration
     */
    private $configuration;

    /**
     * @param QuestionDto $question
     */
    public function __construct(QuestionDto $question)
    {
        $this->configuration = $question->getPlayConfiguration()->getScoringConfiguration();

        parent::__construct($question);
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Component\Editor\AbstractEditor::readAnswer()
     */
    public function readAnswer() : AbstractValueObject
    {
        $answers = [];
        $index = 1;
        $continue = true;
        while ($continue) {
            $continue = false;

            $continue |= $this->processVar('$v' . $index, $answers);
            $continue |= $this->processVar('$r' . $index, $answers);
            $index += 1;
        }

        return new FormulaAnswer($answers);
    }

    /**
     * @param string $name
     * @param array $answers
     * @return bool
     */
    private function processVar(string $name, array &$answers) : bool
    {
        $postvar = $this->getPostVariableName($name);

        if ($this->isPostVarSet($postvar)) {
            $answers[$name] = $this->getPostValue($postvar);

            $unitpostvar = $this->getUnitPostVariableName($name);

            if ($this->isPostVarSet($unitpostvar)) {
                $answers[$name . self::VAR_UNIT] = $this->getPostValue($unitpostvar);
            }

            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Component\Editor\AbstractEditor::generateHtml()
     */
    public function generateHtml() : string
    {
        $output = $this->configuration->getFormula();

        foreach (range(1, count($this->question->getAnswerOptions())) as $resindex) {
            $output = $this->createResult($resindex, $output, $this->question->getPlayConfiguration()->getScoringConfiguration()->getUnits());
        }

        $varindex = 1;
        foreach ($this->configuration->getVariables() as $variable) {
            $output = $this->createVariable($varindex, $output, $variable);
            $varindex += 1;
        }

        return $output;
    }

    /**
     * @param int $index
     * @param string $output
     * @param string $units
     * @return string
     */
    private function createResult(int $index, string $output, ?array $units) : string
    {
        $name = '$r' . $index;

        $html = sprintf('<input type="text" length="20" name="%s" value="%s" />%s', $this->getPostVariableName($name), $this->getAnswerValue($name) ?? '', !empty($units) ? $this->createUnitSelection($units, $name) : '');

        return str_replace($name, $html, $output);
    }

    /**
     * @param string $name
     * @return string|NULL
     */
    private function getAnswerValue(string $name) : ?string
    {
        if (is_null($this->answer) ||
            is_null($this->answer->getValues()) ||
            !array_key_exists($name, $this->answer->getValues()))
        {
            return null;
        }

        return $this->answer->getValues()[$name];
    }

    /**
     * @param string $units
     * @param string $name
     * @return string
     */
    private function createUnitSelection(array $units, string $name) : string
    {
        return sprintf(
            '<select name="%s">%s</select>',
            $this->getUnitPostVariableName($name),
            implode(array_map(function ($unit) use ($name) {
                return sprintf(
                    '<option value="%1$s" %2$s>%1$s</option>',
                    $unit,
                    $this->getAnswerValue($name . self::VAR_UNIT) === $unit ? 'selected="selected"' : ''
                );
            }, $units))
        );
    }

    /**
     * @param int $index
     * @param string $output
     * @param FormulaScoringVariable $def
     * @return string
     */
    private function createVariable(int $index, string $output, FormulaScoringVariable $def) : string
    {
        $name = '$v' . $index;

        $html = sprintf(
            '<input type="hidden" name="%1$s" value="%2$s" />%2$s %3$s',
            $this->getPostVariableName($name),
            $this->getAnswerValue($name) ?? $this->question->getPlayConfiguration()->getScoringConfiguration()->generateVariableValue($def),
            $def->getUnit()
        );

        return str_replace($name, $html, $output);
    }

    /**
     * @param string $name
     * @return string
     */
    private function getPostVariableName(string $name) : string
    {
        return $name . $this->question->getId()->toString();
    }

    /**
     * @param string $name
     * @return string
     */
    private function getUnitPostVariableName(string $name) : string
    {
        return $name . $this->question->getId()->toString() . self::VAR_UNIT;
    }

    /**
     * @return bool
     */
    public function isComplete() : bool
    {
        return true;
    }
}
