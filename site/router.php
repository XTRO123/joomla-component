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
 * Routing class from com_atoms
 *
 * @since  1.0.0
 */
class AtomsRouter extends JComponentRouterView
{
    
    /**
	 * Content Component router constructor
	 *
	 * @param   JApplicationCms  $app   The application object
	 * @param   JMenu            $menu  The menu object to work with
	 */
	public function __construct($app = null, $menu = null)
	{
	    // if do not ajax
        if( $app->input->get('option') != 'com_ajax' ) {
       
            $params = JComponentHelper::getParams('com_atoms');
  		    
            // showcase view
            $showcase = new JComponentRouterViewconfiguration('showcase');
            $showcase->setKey('key'); 
            $this->registerView($showcase);
            
            // tour view
            $tour = new JComponentRouterViewconfiguration('tour');
            $tour->setKey('parent'); 
            $this->registerView($tour);
           
            // tour payment view
            $payment = new JComponentRouterViewconfiguration('payment');
            $payment->setKey('parent'); 
            $this->registerView($payment);
            
            parent::__construct($app, $menu);
    	
            $this->attachRule(new JComponentRouterRulesMenu($this));
    
    		JLoader::register('AtomsRouterRulesLegacy', __DIR__ . '/helpers/legacyrouter.php');
   			$this->attachRule(new AtomsRouterRulesLegacy($this));
    		
        }
        
    }
    
    /**
	 * Method to get the segment(s) for an tour
	 *
	 * @param   string  $id     ID of the tour to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getTourSegment($id, $query)
	{
        return array($query['parent'] => $id);
	}
    
    /**
	 * Method to get the segment(s) for an Payment
	 *
	 * @param   string  $id     ID of the Payment to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getPaymentSegment($id, $query)
	{
        return array($query['parent'] => $id);
	}
    
    /**
	 * Method to get the segment(s) for an Showcase
	 *
	 * @param   string  $id     ID of the Showcase to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getShowcaseSegment($id, $query)
	{
        return array();
	}
    
}

function AtomsBuildRoute(&$query)
{
    $app = JFactory::getApplication();
	$router = new AtomsRouter($app, $app->getMenu());

	return $router->build($query);
}

function AtomsParseRoute($segments)
{
	$app = JFactory::getApplication();
	$router = new AtomsRouter($app, $app->getMenu());

	return $router->parse($segments);
}