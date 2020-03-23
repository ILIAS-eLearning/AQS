<?php
declare(strict_types=1);

namespace srag\asq\UserInterface\Web;

use HTMLPurifier_Config;
use ilHtmlPurifierAbstractLibWrapper;
use ilObjAdvancedEditing;

require_once 'Services/Html/classes/class.ilHtmlPurifierAbstractLibWrapper.php';

/**
 * Class AsqHtmlPurifier
 * 
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class AsqHtmlPurifier extends ilHtmlPurifierAbstractLibWrapper
{
    /**
     * @var AsqHtmlPurifier
     */
    private static $instance;
    
    /**
     * @return AsqHtmlPurifier
     */
    public static function getInstance() : AsqHtmlPurifier {
        if (self::$instance === null) {
            self::$instance = new AsqHtmlPurifier();
        }
        
        return self::$instance;
    }
    
    public function __construct() {
        parent::__construct();
    }
    
	protected function getPurifierType()
	{
		return 'assessment';
	}

	/**
	 * @return	HTMLPurifier_Config Instance of HTMLPurifier_Config
	 */
	protected function getPurifierConfigInstance() : HTMLPurifier_Config
	{
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.DefinitionID', $this->getPurifierType());
		$config->set('HTML.DefinitionRev', 1);
		$config->set('Cache.SerializerPath', ilHtmlPurifierAbstractLibWrapper::_getCacheDirectory());
		$config->set('HTML.Doctype', 'XHTML 1.0 Strict');
		$config->set('HTML.AllowedElements', $this->getAllowedElements());
		$config->set('HTML.ForbiddenAttributes', 'div@style');
		if ($def = $config->maybeGetRawHTMLDefinition()) {
			$def->addAttribute('a', 'target', 'Enum#_blank,_self,_target,_top');
		}

		return $config;
	}
	
	private function getAllowedElements()
	{
		$allowedElements = $this->getElementsUsedForAdvancedEditing();
		
		$allowedElements = $this->makeElementListTinyMceCompliant($allowedElements);
		$allowedElements = $this->removeUnsupportedElements($allowedElements);
		
		return $allowedElements;
	}
	
	private function getElementsUsedForAdvancedEditing()
	{
		include_once 'Services/AdvancedEditing/classes/class.ilObjAdvancedEditing.php';
		return ilObjAdvancedEditing::_getUsedHTMLTags($this->getPurifierType());
	}
} 