<?php

/**
* @package     Joomla.Site
* @subpackage  com_atoms
*
* @copyright   Copyright (C) Atom-S LLC. All rights reserved.
* @license     GNU General Public License version 3 or later; see LICENSE.txt
*/

// No direct access
defined( '_JEXEC' ) or die;

/**
 * @author Korotkov Vadim
 * 
 * @since  1.0.0
 */
class AtomsModelShowcase extends JModelLegacy
{
    /**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.0.0
	 *
	 * @return void
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('site');
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	} 
    
    /**
	 * The method of obtaining tour windows.
	 *	 
	 * @return  object
	 *
	 * @since   1.0.0
	 */
	public function getItems()
	{
        $app    = JFactory::getApplication();
        $items  = new stdClass();
        // get params
        $params = $this->getState('params');
        
        $apiUrl = $params->get('host_url', '');
        $apiKey = $params->get('api_key', '');
        $apiVersion = $params->get('api_version', '');

        try
        {
            // get showcase api url
            $apiShowcaseUrl = AtomsSiteHelper::$apiShowcaseUrl;
            // buil link api tours
            $apiShowcaseUrl = AtomsSiteHelper::buildLink( $apiShowcaseUrl, array('{api_version}', '{api_key}'), array($apiVersion, $apiKey), $apiUrl );
            // connect and get json data of atom-s
            @$items = json_decode(file_get_contents($apiShowcaseUrl));
            
            if( empty((array) $items) ) {
                $this->setError(JText::_('COM_ATOMS_ERROR_TOURS_NOT_FOUND'));
            }
        }
        catch( Exception $e )
        {
            return JError::raiseError(404, $e->getMessage());
        }

		return $items;
	} 
    
}