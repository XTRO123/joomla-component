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
 * View for element tour
 * 
 * @author Korotkov Vadim
 * 
 * @since 1.0.0
 */
class AtomsViewTour extends JViewLegacy
{
    /**
     * 
     * Tour item object
     * 
     * @var    object
     * 
     * @since 1.0.0
     */
	protected $item; 
    
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
     * Page css class
     * 
     * @var    string
     * 
     * @since 1.0.0
     */
    protected $pageclass_sfx;
    
    /**
     * 
     * Key for route
     * 
     * @var    string
     * 
     * @since 1.0.0
     */
    protected $key = '';
    
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
        $this->item = $this->get('Item');
        
        // Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseNotice(204, implode("\n", $errors));
            
            return false;
		}
        
        // get slug
        $slug = $this->_app->input->getString('tour', '');
        if( !empty($slug) ) {
            $this->slug = $slug;
        }
        
        // get selected date of tour
        $selectedDate = $this->_app->input->getString('date', '');
        if( !empty($selectedDate) ) {
            $this->selectedDate = $selectedDate;
        }
        else if( isset($this->item->tour->nearest_trip->time) )
        {
            $this->selectedDate = $this->item->tour->nearest_trip->date;
        }
        
        // get selected time of tour
        $selectedTime = $this->_app->input->getString('time', '');
        if( !empty($selectedTime) ) {
            $this->selectedTime = $selectedTime;
        }
        else if( isset($this->item->tour->nearest_trip->time) )
        {
            $this->selectedTime = $this->item->tour->nearest_trip->time;
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
	    
        JHtml::_('jquery.framework', false); 
        JHtml::_('bootstrap.framework');
        
        // include styles
        $this->_doc->addStyleSheet( JUri::base() . 'media/com_atoms/styles/tour.css' );
        $this->_doc->addStyleSheet( JUri::base() . 'media/com_atoms/styles/slick/slick.css' );
        $this->_doc->addStyleSheet(JUri::base() . 'media/com_atoms/styles/bootstrap/bootstrap.min.css');
        $this->_doc->addStyleSheet(JUri::base() . 'media/com_atoms/styles/fontawesome/font-awesome.min.css');
        
        // fancybox styles
        $this->_doc->addStyleSheet(JUri::base() . 'media/com_atoms/styles/fancybox-3.5.6/jquery.fancybox.min.css');
        
        // cycle2 styles - http://jquery.malsup.com/cycle2/demo/
        $this->_doc->addStyleSheet(JUri::base() . 'media/com_atoms/styles/cycle2/cycle2.css');
        
        // include scripts
        $this->_doc->addScript( JUri::base() . 'media/com_atoms/scripts/slick/slick.min.js' );
        $this->_doc->addScriptDeclaration('jQuery(document).ready(function($){$(\'[data-toggle="tooltip"]\').tooltip();$(".hints").slick({autoplay:true,autoplaySpeed:3000,fade:true,arrows:false});});');
        $this->_doc->addScript( JUri::base() . 'media/com_atoms/scripts/customs.js' );
        
        // fancybox scripts
        $this->_doc->addScript( JUri::base() . 'media/com_atoms/scripts/fancybox-3.5.6/jquery.fancybox.min.js' );
        $this->_doc->addScriptDeclaration("jQuery(function($){
            $('.image-show').fancybox({buttons:['close']});
            $('#atoms-basic-modal').click(function(e){var el, id = $(this).data('open-id');if(id){el=$('.image-show[rel='+id+']:eq(0)');e.preventDefault();el.click();}});
        });");
        
        // cycle2 slider scripts - http://jquery.malsup.com/cycle2/demo/
        $this->_doc->addScript(JUri::base() . 'media/com_atoms/scripts/cycle2/cycle2.min.js');
             
        // Because the application sets a default page title,
   		// we need to get it from the menu item itself
        $menu = $this->_app->getMenu()->getActive();
        
        // set parent id for item menu
        if( isset($menu->query['parent']) && isset($menu->query['view']) && $menu->query['view'] == 'tour' ) 
        {
            $this->key = $menu->query['parent'];
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
            // set title
            $pathway->setItemName((count($pathway->getPathway())-1), $this->item->tour->name);
            //Then use setPathway to update the breadcrumbs with your modified path!
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