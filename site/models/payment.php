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
class AtomsModelPayment extends JModelLegacy
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
	    
        $tour = new stdClass();
        
        try
        {
            // get tour model
            JLoader::register('AtomsModelTour', JPATH_COMPONENT_SITE . '/models/tour.php');
            $tourModel = new AtomsModelTour();
        
            $tour = $tourModel->getItem();
            
            if( count($errors = $tourModel->getErrors()) ) {
                $this->setError(implode("\n", $errors));
            }
            
        }
        catch( Exception $e )
        {
            return JError::raiseError(404, $e->getMessage());
        }
        
        return $tour;
        
	}
    
}