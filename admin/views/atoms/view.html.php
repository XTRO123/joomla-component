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
 * View for instruction
 * 
 * @author Korotkov Vadim
 * 
 * @since 1.0.0
 */
class AtomsViewAtoms extends JViewLegacy
{

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   1.0.0
	 */
	public function display($tpl = null)
	{
	    
		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
        
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function addToolbar()
	{
		
		$user = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_ATOMS_MANAGER_AROMS'), 'bookmark');
		
		if ($user->authorise('core.admin', 'com_atoms'))
		{
			JToolbarHelper::preferences('com_atoms');
		}

	}

}