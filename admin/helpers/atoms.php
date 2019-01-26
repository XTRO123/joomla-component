<?php

defined( '_JEXEC' ) or die;

/**
 * Class AtomsHelper
 */
class AtomsHelper
{
	/**
	 * Добавление подменю
	 * @param String $vName
	 */
	static function addSubmenu( $vName )
	{

	}

	/**
	 * Получаем доступные действия для текущего пользователя
	 * @return JObject
	 */
	public static function getActions()
	{
		$user = JFactory::getUser();
		$result = new JObject;
		$assetName = 'com_atoms';
		$actions = JAccess::getActions( $assetName );
		foreach ( $actions as $action ) {
			$result->set( $action->name, $user->authorise( $action->name, $assetName ) );
		}
		return $result;
	}
}