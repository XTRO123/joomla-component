<?php

/**
* @package     Joomla.Site
* @subpackage  com_atoms
*
* @copyright   Copyright (C) Atom-S LLC. All rights reserved.
* @license     GNU General Public License version 3 or later; see LICENSE.txt
*/

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Component\Router\RouterView;

/**
 * Legacy routing rules class from com_atoms
 *
 * @since       1.0.0
 * @deprecated  4.0
 */
class AtomsRouterRulesLegacy implements JComponentRouterRulesInterface
{
	
    /**
	 * Class constructor.
	 *
	 * @param   RouterView  $router  Router this rule belongs to
	 *
	 * @since   1.0.0
	 */
	public function __construct($router)
	{
		$this->router = $router;
	}

	/**
	 * Finds the right Itemid for this query
	 *
	 * @param   array  &$query  The query array to process
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function preprocess(&$query)
	{
	}

	/**
	 * Build the route for the com_srsurvey component
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   1.0.0
	 */
	public function build(&$query, &$segments)
	{

        if (empty($query['Itemid']))
		{
			$menuItem = $this->router->menu->getActive();
            $menuItemGiven = false; 
		}
		else
		{
			$menuItem = $this->router->menu->getItem($query['Itemid']);
            $menuItemGiven = true; 
		}

        // Check again
		if ($menuItemGiven && isset($menuItem) && $menuItem->component != 'com_atoms')
		{
			$menuItemGiven = false;
			unset($query['Itemid']);
		}

		if (isset($query['view']))
		{
			$view = $query['view'];
		}
		else
		{
            // We need to have a view in the query or it is an invalid URL
			return;
		}

        // Are we dealing with an article or category that is attached to a menu item?
		if ($menuItem !== null && $menuItem->query['view'] == $query['view'] )
		{
			
            unset($query['view']);
            
			if (isset($query['layout']))
			{
				unset($query['layout']);
			}

            if( isset($query['key']) ) 
            {
                unset($query['key']);
            }

            if( isset($query['parent']) ) 
            {
                unset($query['parent']);
            }
            
            if( $menuItemGiven && isset($query['tour']) )
            {
                $segments[] = $query['tour'];
                unset($query['tour']);
            }
            
			return;
		}
       
        // check
		if ($view == 'payment' || $view == 'tour')
		{
			if ($menuItemGiven && $menuItem->query['view'] != $query['view'] )
			{
				$segments[] = $view;
			}
            
            if( $menuItemGiven && isset($query['tour']) )
            {
                $segments[] = $query['tour'];
                unset($query['tour']);
            }
            
			unset($query['view']);
            
		}
                
        // unset layout
        if (isset($query['layout']))
		{
			if (!empty($query['Itemid']) && isset($menuItem->query['layout']))
			{
				if ($query['layout'] == $menuItem->query['layout'])
				{
					unset($query['layout']);
				}
			}
			else
			{
				if ($query['layout'] === 'default')
				{
					unset($query['layout']);
				}
			}
		}
        
	}

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @since   1.0.0
	 */
	public function parse(&$segments, &$vars)
	{
		// Get the active menu item.
		$item	= $this->router->menu->getActive();
		
		// Count route segments
		$count = count($segments);

		// Standard routing
	    if ( $count == 2 && !isset($item))
		{
			$vars['view'] = $segments[0];
			$vars['tour'] = $segments[$count - 1];
		}
        elseif( $count == 1 && isset($item->query['view']) )
        {
           
            switch( $item->query['view'] )
            {
                case 'tour':
                case 'payment':
                    $vars['view'] = $item->query['view'];
                    break;
                
                default:;
                    break;
            }
            
            $vars['tour'] = $segments[0];

        }
        else
        {
            $vars['view'] = $segments[0];
            $vars['tour'] = $segments[1];
        }
                
        return $vars;
        
	}
    
}