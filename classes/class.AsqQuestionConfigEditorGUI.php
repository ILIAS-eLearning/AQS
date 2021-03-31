<?php
declare(strict_types=1);

use ILIAS\DI\HTTPServices;
use ILIAS\DI\UIServices;
use srag\asq\Application\Service\AuthoringContextContainer;
use srag\asq\Domain\QuestionDto;
use srag\asq\UserInterface\Web\Form\QuestionFormGUI;
use ILIAS\Data\UUID\Uuid;
use srag\asq\Application\Service\ASQServices;

/**
 * Class AsqQuestionConfigEditorGUI
 *
 * Displays Question configuration Form used to edit Question
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 */
class AsqQuestionConfigEditorGUI
{
    const CMD_SHOW_FORM = 'showForm';
    const CMD_SAVE_FORM = 'saveForm';
    const CMD_SAVE_AND_RETURN = 'saveAndReturn';
    const PARAM_REVISON_NAME = 'revisionName';

    /**
     * @var AuthoringContextContainer
     */
    protected $contextContainer;

    /**
     * @var QuestionDto
     */
    private $question;

    /**
     * @var ilLanguage
     */
    private $language;

    /**
     * @var UIServices
     */
    private $ui;

    /**
     * @var ilCtrl
     */
    private $ctrl;

    /**
     * @var ASQServices
     */
    private $asq;

    /**
     * @var HTTPServices
     */
    private $http;

    /**
     * @param AuthoringContextContainer $contextContainer
     * @param Uuid $questionId
     * @param ilLanguage $language
     * @param UIServices $ui
     * @param ilCtrl $ctrl
     * @param ASQServices $asq
     */
    public function __construct(
        AuthoringContextContainer $contextContainer,
        Uuid $questionId,
        ilLanguage $language,
        UIServices $ui,
        ilCtrl $ctrl,
        ASQServices $asq,
        HTTPServices $http
    ) {
        $this->asq = $asq;
        $this->contextContainer = $contextContainer;
        $this->language = $language;
        $this->ui = $ui;
        $this->ctrl = $ctrl;
        $this->http = $http;

        $query = $this->http->request()->getQueryParams();
        if (isset($query[self::PARAM_REVISON_NAME])) {
            $revision_name = $query[self::PARAM_REVISON_NAME];
        }

        if (is_null($revision_name)) {
            $this->question = $this->asq->question()->getQuestionByQuestionId($questionId);
        } else {
            $this->question = $this->asq->question()->getQuestionRevision($questionId, $revision_name);
            ilutil::sendInfo($this->language->txt('asq_revision_loaded'));
        }
    }


    public function executeCommand() : void
    {
        switch ($this->ctrl->getNextClass()) {
            case strtolower(self::class):
            default:

                $cmd = $this->ctrl->getCmd(self::CMD_SHOW_FORM);
                $this->{$cmd}();
        }
    }

    /**
     * @param ?QuestionFormGUI $form
     */
    protected function showForm(?QuestionFormGUI $form = null) : void
    {
        if ($form === null) {
            $form = $this->buildForm();
        }

        $this->ui->mainTemplate()->setContent($form->getHTML());
    }


    /**
     * @throws Exception
     */
    protected function saveForm() : void
    {
        $form = $this->buildForm();

        $this->saveQuestion($form);

        ilutil::sendInfo($this->language->txt('asq_question_saved'), true);

        $form->checkInput();
        $this->showForm($form);
    }

    /**
     * @throws Exception
     */
    protected function saveAndReturn() : void
    {
        $form = $this->buildForm();

        $this->saveQuestion($form);

        if (!$form->checkInput()) {
            $this->showForm($form);
            return;
        }

        $this->ctrl->redirectToUrl(str_replace(
            '&amp;',
            '&',
            $this->contextContainer->getBackLink()->getAction()
        ));
    }

    private function saveQuestion(QuestionFormGUI $form) : void
    {
        $changes = $form->getQuestion();
        $this->question->setData($changes->getData());
        $this->question->setPlayConfiguration($changes->getPlayConfiguration());
        $this->question->setAnswerOptions($changes->getAnswerOptions());
        $this->asq->question()->saveQuestion($this->question);
    }

    /**
     * @return QuestionFormGUI
     * @throws Exception
     */
    private function buildForm() : QuestionFormGUI
    {
        $form = $this->asq->ui()->getQuestionEditForm(
            $this->question,
            $this->ctrl->getFormAction($this, self::CMD_SAVE_FORM)
        );

//         $form->addCommandButton(self::CMD_SAVE_AND_RETURN, $this->language->txt('save_return'));
//         $form->addCommandButton(self::CMD_SAVE_FORM, $this->language->txt('save'));

        return $form;
    }
}
