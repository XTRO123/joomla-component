<?php

/**
* @package     Joomla.Site
* @subpackage  com_atoms
*
* @copyright   Copyright (C) Atom-S LLC. All rights reserved.
* @license     GNU General Public License version 2 or later; see LICENSE.txt
*/

defined( '_JEXEC' ) or die; // No direct access

/**
 * Default Controller
 * @author Korotkov Vadim
 */
class AtomsController extends JControllerLegacy
{
    /**
	 * The default view for the display method.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	public $default_view = 'showcase';
    
    /**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
	 *
	 * @since   1.0.0
	 */
	public function __construct($config = array())
	{
        parent::__construct($config);
	}
    
    /**
	 * Method to display a view.
	 * @param bool $cachable
	 * @param array $urlparams
	 * @return JControllerLegacy
	 */
	function display( $cachable = false, $urlparams = array() )
	{
		
        parent::display( $cachable, $urlparams );
        
		return $this;
    
    }

}