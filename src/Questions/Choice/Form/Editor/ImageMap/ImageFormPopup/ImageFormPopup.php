<?php
declare(strict_types=1);

namespace srag\asq\Questions\Choice\Form\Editor\ImageMap\ImageFormPopup;

use ILIAS\UI\Implementation\Component\Input\Field\Input;
use Closure;

/**
 * Class ImageFormPopup
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class ImageFormPopup extends Input
{
    /**
     * {@inheritDoc}
     * @see \ILIAS\UI\Implementation\Component\Input\Field\Input::isClientSideValueOk()
     */
    protected function isClientSideValueOk($value) : bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     * @see \ILIAS\UI\Implementation\Component\Input\Field\Input::getConstraintForRequirement()
     */
    protected function getConstraintForRequirement()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \ILIAS\UI\Component\Input\Field\FormInput::getUpdateOnLoadCode()
     */
    public function getUpdateOnLoadCode() : Closure
    {
        return null;
    }
}
