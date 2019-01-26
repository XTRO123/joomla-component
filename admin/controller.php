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
 * Default Controller
 * 
 * @author Korotkov Vadim
 * 
 * @since 1.0.0
 */
class AtomsController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 * @param bool $cachable
	 * @param array $urlparams
	 * @return JControllerLegacy
	 */
	function display( $cachable = false, $urlparams = array() )
	{
		$this->default_view = '';
		parent::display( $cachable, $urlparams );
		return $this;
	}
    
}