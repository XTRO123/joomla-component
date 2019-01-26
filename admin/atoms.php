<?php

/**
* @package     Joomla.Site
* @subpackage  com_atoms
*
* @copyright   Copyright (C) Atom-S LLC. All rights reserved.
* @license     GNU General Public License version 3 or later; see LICENSE.txt
*/

defined( '_JEXEC' ) or die; // No direct access

/**
 * Component atoms
 * 
 * @author Korotkov Vadim
 * 
 */
require_once JPATH_COMPONENT.'/helpers/atoms.php';
$controller = JControllerLegacy::getInstance( 'atoms' );
$controller->execute( JFactory::getApplication()->input->get( 'task' ) );
$controller->redirect();