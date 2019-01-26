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
 * View for elements of showcase
 * 
 * @author Korotkov Vadim
 * 
 * @since 1.0.0
 */
class AtomsViewShowcase extends JViewLegacy
{
    
    /**
     * 
     * Items tours
     * 
     * @var    object
     * 
     * @since 1.0.0
     */
    protected $items; 
   
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
     * 
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
	 * Method of display current template
	 * @param type $tpl
	 */
	public function display( $tpl = null )
	{
		
        $this->_app = JFactory::getApplication();
        $this->_doc = JFactory::getDocument();
       
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
            
        // Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseNotice(204, implode("\n", $errors));
            
            return false;
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
	    
        JHtml::_('jquery.framework', true); 
        JHtml::_('bootstrap.framework');
        
        // include styles
        $this->_doc->addStyleSheet( JUri::base() . 'media/com_atoms/styles/showcase.css' );
        $this->_doc->addStyleSheet(JUri::base() . 'media/com_atoms/styles/fontawesome/font-awesome.min.css');
        
        // include scripts
        $this->_doc->addScriptDeclaration('jQuery(document).ready(function($){$(\'[data-toggle="tooltip"]\').tooltip();});');
        
        // Because the application sets a default page title,
   		// we need to get it from the menu item itself
        $menu = $this->_app->getMenu()->getActive();
        
        $this->key = $menu->query['key'];
        
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