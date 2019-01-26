<?php

/**
* @package     Joomla.Site
* @subpackage  com_atoms
*
* @copyright   Copyright (C) Atom-S LLC. All rights reserved.
* @license     GNU General Public License version 3 or later; see LICENSE.txt
*/

defined('_JEXEC') or die;

/**
 * Duration Html Helper
 *
 * @since  1.0.0
 */
abstract class JHtmlDuration
{
    /**
     * 
     * Ending 
     * 
     * @var string
     * 
     * @since  1.0.0
     * 
     */
    static private $_ending = array(
        'h' => array('HOUR', 'OCLOCK', 'HOURS'),
        'd' => array('DAY', 'OFDAYS', 'DAYS'),
        'n' => array('NIGHT', 'AM', 'NIGHTS'),
        'y' => array('YEAR', 'OFYEAR', 'OLD'),
    );
    
    /**
     * 
     * Method duration
     * 
     * @param   int         $number, declination number
     * @param   mixed       $type,  declination type 
     * @param   boolean     $ending, true - end use, false - not use
     * @param   string      $separator, separator after the number
     * @param   string      $space
     * 
     * @return string
     * 
     * @since  1.0.0
     */
    public static function duration( $number = 0, $type = null, $ending = true, $separator = '', $space = ' ' ) {
        
        $string = '';
        
        if( isset($type) && isset(self::$_ending[$type]) ) 
        {
        
            switch( ($number >= 20) ? $number % 10 : $number ) {
                case 1:
                    $string = $number . (($ending) ? $space.JText::_('COM_ATOMS_DURATION_'.self::$_ending[$type][0]) : '');
                    break;
                case 2:
                case 3:
                case 4:
                    $string = $number . (($ending) ? $space.JText::_('COM_ATOMS_DURATION_'.self::$_ending[$type][1]) : '');
                    break;
                default:
                    $string = $number . (($ending) ? $space.JText::_('COM_ATOMS_DURATION_'.self::$_ending[$type][2]) : '');
                    break;
            }
        
            $string .= $separator;
        
        }
        
        return $string;
        
    }	
    
}