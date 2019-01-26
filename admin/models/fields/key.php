<?php

defined('JPATH_BASE') or die;

/**
 * Clicks Field class for the Joomla Framework.
 *
 * @since  1.6
 */
class JFormFieldKey extends JFormField
{
	    
    /**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'key';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 *
	 * @since   1.6
	 */
	protected function getInput()
	{
	    // generation key if new menuitem
        if( empty($this->value) ) {
            
            $db    = JFactory::getDbo();
		    $query = $db->getQuery(true);
            
            $this->value = md5(time().rand(0, 99999));
            
            do
            {
                
                $query
                    ->select('a.id')
    			    ->from('#__menu AS a')
                    ->where('`a`.`link` = \'index.php?option=com_atoms&view=showcase&key='.$this->value.'\'');
                
                $result = $db->setQuery($query)->loadResult();
                $query->clear();
                
            }
            while(isset($result));
            
        }
        
		return '<input type="hidden" name="'. $this->name .'" value="'.$this->value.'" />';
        
	}
     
} 