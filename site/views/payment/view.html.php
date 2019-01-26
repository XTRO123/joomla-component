<?php

/**
* @package     Joomla.Site
* @subpackage  com_atoms
*
* @copyright   Copyright (C) Atom-S LLC. All rights reserved.
* @license     GNU General Public License version 3 or later; see LICENSE.txt
*/

defined( '_JEXEC' ) or die; // No direct access

use Joomla\Uri\Uri;

/**
 * View for element payment
 * 
 * @author Korotkov Vadim
 * 
 * @since 1.0.0
 */
class AtomsViewPayment extends JViewLegacy
{
    /**
     * 
     * Tour item object
     * 
     * @var    object
     * 
     * @since 1.0.0
     */
    protected $tour; 
    
    /**
     * 
     * Service data info component
     * 
     * @var    object
     * 
     * @since 1.0.0
     */
    protected $state; 
    
    /**
     * 
     * Params component
     * 
     * @var    object
     * 
     * @since 1.0.0
     */
    protected $params;
    
    /**
     * Document object
     * 
     * @var    object
     * 
     * @since 1.0.0
     */
    protected $_doc; 

    /**
     * Application object
     * 
     * @var    object
     * 
     * @since 1.0.0
     */
    protected $_app;
    
    /**
     * 
     * API host url in showcase
     * 
     * @var    string
     * 
     * @since 1.0.0
     */
    protected $apiHost = '';
    
    /**
     * 
     * API key in showcase
     * 
     * @var    string
     * 
     * @since 1.0.0
     */
    protected $apiKey = '';
    
    /**
     * 
     * API version in showcase
     * 
     * @var    string
     * 
     * @since 1.0.0
     */
    protected $apiVersion = '';
    
    /**
     * 
     * Slug for tour
     * 
     * @var    string
     * 
     * @since 1.0.0
     */
    protected $slug = '';
    
    /**
     * 
     * Date selected tour
     * 
     * @var    string
     * 
     * @since 1.0.0
     */
    protected $selectedDate = '';
    
    /**
     * 
     * Time selected tour
     * 
     * @var    string
     * 
     * @since 1.0.0
     */
    protected $selectedTime = '';
    
	/**
	 * Method of display current template
	 * @param type $tpl
	 */
	public function display( $tpl = null )
	{
		
        $this->_app = JFactory::getApplication();
        $this->_doc = JFactory::getDocument();
        
        $this->state = $this->get('State');
        $this->tour = $this->get('Item');
        
        // Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseNotice(204, implode("\n", $errors));
            
            return false;
		}
        
        // get slug
        $slug = $this->_app->input->getString('tour', '');
        if( !empty($slug) ) 
        {
            $this->slug = $slug;
        }
        
        // get date
        $selectedDate = $this->_app->input->getString('date', '');
        if( !empty($selectedDate) ) 
        {
            $this->selectedDate = $selectedDate;
        }
        else if( isset($this->tour->tour->nearest_trip->time) )
        {
            $this->selectedDate = $this->tour->tour->nearest_trip->date;
        }
        
        // get selected time of tour
        $selectedTime = $this->_app->input->getString('time', '');
        if( !empty($selectedTime) ) 
        {
            $this->selectedTime = $selectedTime;
        }
        else if( isset($this->tour->tour->nearest_trip->time) )
        {
            $this->selectedTime = $this->tour->tour->nearest_trip->time;
        }
        
        // Otherwise, showcase params override menu item params
		$this->params = $this->state->get('params'); 
        
        $this->_prepareDocument();  
        
        parent::display( $tpl );
	}

    /**
	 * Prepares the document.
	 *
	 * @return  void.
	 */
	protected function _prepareDocument()
	{
	    
        // Because the application sets a default page title,
   		// we need to get it from the menu item itself
        $menu = $this->_app->getMenu()->getActive();
        
        // set parent id for item menu
        if( isset($menu->query['parent']) && isset($menu->query['view']) && $menu->query['view'] == 'payment' ) 
        {
            $parent = $menu->query['parent'];
            $params = $this->_app->getMenu()->getItems('link', 'index.php?option=com_atoms&view=showcase&key='.$parent, true)->getParams();
            $this->apiKey = $params->get('api_key', '');
            $this->apiVersion = $params->get('api_version', '');
            $this->apiHost = $params->get('host_url', '');
                
            if( empty($this->apiKey) && empty($this->apiVersion) )
            {
                JError::raiseError(204, JText::_('COM_ATOMS_ERROR_TOUR_PARENT_ITEM_MENU_NOT_FOUND'));
            }
        }
        else
        {
            JError::raiseNotice(204, JText::_('COM_ATOMS_ERROR_TOUR_PARENT_ITEM_MENU_NOT_FOUND'));
        }
        
		// Navigation
        if ($menu && ($menu->query['option'] !== 'com_atoms' || $menu->query['view'] !== 'showcase'))
		{
			
            $pathway = $this->_app->getPathway();
            //this function returns the array of pathway objects, allowing you to modify it
            $newPathway = $pathway->getPathway();
            if( count($newPathway) > 0 ) {
                $uri = new JUri();
                foreach( $newPathway as $k => $path ) {
                    $uri->parse($path->link);
                    $query = $uri->getQuery(true);
                    
                    if( isset($query['view']) && $query['view'] == 'tour' && isset($query['parent']) ) {
                        // create tour link
                        $newPathway[$k]->link = AtomsSiteHelper::createLink($this->slug, array('tour'), array('parent', $query['parent']));
                        // set title
                        $pathway->setItemName($k, $this->tour->tour->name);
                    }
                }
            }
            
            $pathway->setPathway($newPathway);
            
		}
        
        $title = $this->params->get('page_title', '');
    
        // Check for empty title and add site name if param is set
		if (empty($title))
		{
			$title = $this->_app->get('sitename');
		}
		elseif ($this->_app->get('sitename_pagetitles', 0) == 1 )
		{
            $title = JText::sprintf('JPAGETITLE', $this->_app->get('sitename'), (trim($title) == '' ? $menu->title : $title) );
        }
		elseif ($this->_app->get('sitename_pagetitles', 0) == 2 )
		{
			$title = JText::sprintf('JPAGETITLE', (trim($title) == '' ? $menu->title : $title), $this->_app->get('sitename'));
		}

		if (!empty($title))
		{
			$this->_doc->setTitle($title);
		}
        
		if ($this->params->get('menu-meta_description'))
		{
			$this->_doc->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->_doc->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots')); 
        {
            $this->_doc->setMetaData('robots', $this->params->get('robots'));
        }
        
        if( $rights = $this->_app->get('MetaRights', '') )
        {
            $this->_doc->setMetaData('rights', $rights);
        }
        
		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx')); 
        
	}

}