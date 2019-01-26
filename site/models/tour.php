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
 * @since 1.0.0
 */
class AtomsModelTour extends JModelLegacy
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
	 * Method of receiving a tour card.
	 *	 
	 * @return  object
	 *
	 * @since   1.0.0
	 */
	public function getItem()
	{
        $item   = new stdClass();
        $app    = JFactory::getApplication();
		$input  = $app->input;
        // get parameters
        $slug   = $input->getString('tour', '');
        $tourDate = $input->getString('date', '');
        $tourTime = $input->getVar('time', '');
        
        try
        {
            // connect service if exist slug
            if( !empty($slug) ) {
                
                /* check parent key */
                $activeMenuitem = $app->getMenu()->getActive();
                if( !isset($activeMenuitem->query['parent']) ) 
                {
                    $this->setError(JText::_('COM_ATOMS_ERROR_TOUR_NOT_PARENT_KEY'));
                }
                // get params of parent menuitem
                else
                {
                    $parent = $activeMenuitem->query['parent'];
                    $params = $app->getMenu()->getItems('link', 'index.php?option=com_atoms&view=showcase&key='.$parent, true)->getParams();
                    $apiUrl = $params->get('host_url', '');
                    $apiKey = $params->get('api_key', '');
                    $apiVersion = $params->get('api_version', '');
                    
                    // get tour api url
                    $apiTourUrl = AtomsSiteHelper::$apiTourUrl;
                    // buil link api tour
                    $apiTourUrl = AtomsSiteHelper::buildLink( $apiTourUrl, array('{api_version}', '{api_key}', '{slug}'), array($apiVersion, $apiKey, $slug), $apiUrl );
                    
                    if( !empty($tourDate) ) $apiTourUrl .= '?tour_date='.$tourDate;
                    if( !empty($tourTime) ) $apiTourUrl .= (!empty($tourDate)?'&':'?') . 'tour_time='.$tourTime;
                    
                    // connect and get json data of atom-s
                    @$item = json_decode(file_get_contents($apiTourUrl));
                    
                    if( empty((array) $item) ) {
                        //throw new Exception(JText::_('COM_ATOMS_ERROR_TOUR_NOT_FOUND'));
                        $this->setError(JText::_('COM_ATOMS_ERROR_TOUR_NOT_FOUND'));
                    }
                }
                
            }
            else
            {
                $this->setError(JText::_('COM_ATOMS_ERROR_TOUR_NOT_FOUND'));
            }
                        
        }
        catch( Exception $e )
        {
            return JError::raiseError(404, $e->getMessage());
        }

		return $item;
	} 
    
}