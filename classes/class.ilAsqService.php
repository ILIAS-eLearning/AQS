<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class ilAsqService
 *
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Services/AssessmentQuestion
 */
class ilAsqService
{
	/**
	 * @param ilCtrl $ctrl
	 * @return string
	 */
	public function fetchNextAuthoringCommandClass($nextClass)
	{
		global $DIC; /* @var ILIAS\DI\Container $DIC */
		
		$row = $DIC->database()->fetchAssoc($DIC->database()->queryF(
			"SELECT COUNT(question_type_id) cnt FROM qpl_qst_type WHERE ctrl_class = %s",
			array('text'), array($nextClass)
		));
		
		if( $row['cnt'] )
		{
			// current next class is indeed an authoring ctrl class,
			// return it to have the switch(nextclass) case matching
			return $nextClass;
		}
		
		// the interface that NOT represents a valid ctrl class,
		// this will lead to a non matching switch(nextclass) case
		return 'ilasqquestionauthoring';
	}
	
	/**
	 * @param ilQTIItem $qtiItem
	 * @return string
	 */
	public function determineQuestionTypeByQtiItem(ilQTIItem $qtiItem)
	{
		// the qti service parses ILIAS question types, so use it
		// although this may get changed in the future 
		return $qtiItem->getQuestiontype();
	}
	
	/**
	 * @param integer $parentObjectId
	 * @param string $questionTitle
	 * @return bool
	 */
	public function questionTitleExists($parentObjectId, $questionTitle)
	{
		global $DIC; /* @var ILIAS\DI\Container $DIC */
		
		$row = $DIC->database()->fetchAssoc($DIC->database()->queryF(
			"SELECT COUNT(question_id) cnt FROM qpl_questions WHERE obj_fi = %s AND title = %s",
			array('integer', 'text'), array($parentObjectId, $questionTitle)
		));
		
		return $row['cnt'] > 0;
	}
	
	/**
	 * @param integer $questionId
	 * @return integer
	 */
	public function lookupParentObjId($questionId)
	{
		global $DIC; /* @var ILIAS\DI\Container $DIC */
		
		$res = $DIC->database()->queryF(
			"SELECT obj_fi FROM qpl_questions WHERE question_id = %s",
			array('integer'), array($questionId)
		);
		
		while( $row = $DIC->database()->fetchAssoc($res) )
		{
			$row['obj_fi'];
		}
		
		throw new InvalidArgumentException();
	}
}