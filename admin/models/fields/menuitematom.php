<?php

defined('JPATH_BASE') or die;

// Including fallback code for HTML5 non supported browsers.
JHtml::_('jquery.framework');
JHtml::_('script', 'system/html5fallback.js', false, true);

/**
 * Clicks Field class for the Joomla Framework.
 *
 * @since  1.6
 */
class JFormFieldMenuitematom extends JFormField
{
	
    
    /**
	 * The checked type.
	 *
	 * @var		string
	 */
    protected $checked = '';
    
    /**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'menuitematom';

    /**
	 * Cached array of the menus.
	 *
	 * @var    array
	 * @since  1.6
	 */
	protected $menus = array(); 

    /**
	 * Cached array of the menus items.
	 *
	 * @var    array
	 * @since  1.6
	 */
	protected $items = array();

    /**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed              $value    The form field value to validate.
	 * @param   string             $group    The field name group control value. This acts as as an array container for the field.
	 *                                       For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                       full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   11.1
	 */
	public function setup(\SimpleXMLElement $element, $value, $group = null)
	{
		// Make sure there is a valid JFormField XML element.
		if ((string) $element->getName() != 'field')
		{
			return false;
		}

		// Set the field checked.
		if ($element['check'])
		{
			$this->checked = (string) $element['check'];
		}
        else
        {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ATOMS_MANAGER_MENU_NOT_FOUND_DESC'), 'error');
        }
		
        return parent::setup($element, $value, $group);
        
	}

    /**
	 * Get a list of the available menus.
	 *
	 * @param   int  $clientId  The client id
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	protected function menus($clientId = 0)
	{
		$key = serialize($clientId);

		if (!isset($this->menus[$key]))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select($db->qn(array('id', 'menutype', 'title', 'client_id'), array('id', 'value', 'text', 'client_id')))
				->from($db->quoteName('#__menu_types'))
				->order('client_id, title');

			if (isset($clientId))
			{
				$query->where('client_id = ' . (int) $clientId);
			}

			$this->menus[$key] = $db->setQuery($query)->loadObjectList();
		}

		return $this->menus[$key];
	}

    /**
	 * Returns an array of menu items grouped by menu.
	 *
	 * @param   array  $config  An array of configuration options [published, checkacl, clientid].
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	protected function menuItems($config = array())
	{
		$app = JFactory::getApplication();
        $key = serialize($config);

		if (empty($this->items[$key]))
		{
			// B/C - not passed  = 0, null can be passed for both clients
			$clientId = array_key_exists('clientid', $config) ? $config['clientid'] : 0;
			$menus    = $this->menus($clientId);

			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
                ->select('a.id AS value, a.title AS text, a.level, a.menutype, a.client_id, a.link, a.published')
				->from('#__menu AS a')
				->where('a.parent_id > 0')
                ->where('`link` LIKE \'%index.php?option=com_atoms&view=showcase%\'');
                
			// Filter on the client id
			if (isset($clientId))
			{
				$query->where('a.client_id = ' . (int) $clientId);
			}

			// Filter on the published state
			if (isset($config['published']))
			{
				if (is_numeric($config['published']))
				{
					$query->where('a.published = ' . (int) $config['published']);
				}
				elseif ($config['published'] === '')
				{
					$query->where('a.published IN (0,1)');
				}
			}

			$query->order('a.lft');

			$db->setQuery($query);
			$items = $db->loadObjectList();
            $query->clear();

			// Collate menu items based on menutype
			$lookup = array();

			foreach ($items as &$item)
			{
				if (!isset($lookup[$item->menutype]))
				{
					$lookup[$item->menutype] = array();
				}

				$lookup[$item->menutype][] = &$item;

				//$item->text = str_repeat('- ', $item->level) . $item->text;
                
                $item->key = $this->getParamValue($item->link, 'key');
                
			}

			$this->items[$key] = array();

			foreach ($menus as &$menu)
			{
				
				// Start group:
				$this->items[$key][] = JHtml::_('select.optgroup', $menu->text);

				// Menu items:
				if (isset($lookup[$menu->value]))
				{
					foreach ($lookup[$menu->value] as &$item)
					{
                        if( isset($item->key) ) {
                            
                            // get selected showcase
                            $query
                                ->select('a.id, a.published, a.title')
                                ->from('#__menu AS a')
                                ->where('a.link = \'index.php?option=com_atoms&view=' . $this->checked . '&parent=' . $item->key . '\'');
                            $selected = $db->setQuery($query)->loadObject();
                            $query->clear();
                            
                            // not additional if selected
                            if( $this->value == $item->key || !isset($selected) ) 
                            {
                                $this->items[$key][] = JHtml::_('select.option', $item->key, $item->text);
                            }
                            else if( isset($selected) && ($selected->published == '0' || $selected->published == '-2') )
                            {
                                if( $selected->published == '0' ) 
                                {
                                    $app->enqueueMessage(JText::sprintf('COM_ATOMS_FIELD_WARNING_MENUITEM_NOT_PUBLISHED', $item->text, $selected->title, $selected->id), 'warning');
                                }
                                else
                                {
                                    $app->enqueueMessage(JText::sprintf('COM_ATOMS_FIELD_WARNING_MENUITEM_TRASH', $item->text, $selected->title, $selected->id), 'warning');
                                }
                            }
                        }
					}
				}

				// Finish group:
				$this->items[$key][] = JHtml::_('select.optgroup', $menu->text);
			}
		}

		return $this->items[$key];
	} 

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 *
	 * @since   1.6
	 */
	protected function getInput()
	{
	    
        $config = array('published' => '');
		$options = $this->menuItems($config);
        
        if( !empty($options) ) {
            
            return JHtml::_(
    			'select.genericlist', $options, $this->name,
    			array(
    				'id'             => $this->id,
    				'list.attr'      => 'class="inputbox" size="1"',
    				'list.select'    => (int) $this->value,
    				'list.translate' => false,
    			)
    		);
            
        }
        else
        {
            return JText::_('COM_ATOMS_MANAGER_MENU_NOT_FOUND');
        }
        
	}
    
    /**
     * 
     * Get paramater
     * 
     * @param   string    $text   string with parameter
	 * @param   string    $key    search parameter
     * 
     * @return string
     * 
     */
    protected function getParamValue($text, $key){
        $value = null;
        if(preg_match("/".$key."=(.*)$/si", $text, $matches)) {
          $value = $matches[1];
        }
        return $value;
    }
       
} 