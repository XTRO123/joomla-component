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
 * Component helper
 * 
 * @author Korotkov Vadim
 * 
 * @since 1.0.0
 */
class AtomsSiteHelper
{
	
    /**
     * 
     * Prefix API url atom-s system
     * 
     * @var string
     * @since  1.0.0
     * 
     */
    static $prefixApiUrl = "api/";
    
    /**
     * 
     * API url atom-s system
     * 
     * @var string
     * @since  1.0.0
     * 
     */
    static $apiUrl = "https://atom-s-alvoro.c9users.io/api/";
    
    /**
     * 
     * API url for list tours in atom-s system
     * 
     * @var string
     * @since  1.0.0
     * 
     */
    static $apiShowcaseUrl = "v{api_version}/{api_key}/search.json";
    
    /**
     * 
     * API url for list alone tour in atom-s system
     * 
     * @var string
     * @since  1.0.0
     * 
     */
    static $apiTourUrl = "v{api_version}/{api_key}/search/tour/{slug}.json";
    
    /**
     * 
     * API url for payment tour in atom-s system
     *      
     * @var string
     * @since  1.0.0
     * 
     */
    static $apiPaymentTourUrl = "/api/v{api_version}/{api_key}/search/tour/{slug}/{package_constructor}";
    
    /**
     * 
     * The Method build link, for atom-s
     * 
     * @param   string   $link, link to connect to the system
     * @param   array    $find, search for replaceable parameters in the link
     * @param   array    $replaced, replacement of parameters from $find
     * @param   string   $apiUrl, adding host link
     * @param   boolean  $prefixApi, additional prefix api
     * 
     * @return string
     * 
     * @since  1.0.0
     */
    static public function buildLink( $link = '', $find = array(), $replaced = array(), $apiUrl = '', $prefixApi = true ) {
        
        if( !empty($link) && !empty($find) ) 
        {
            // perse and build link
            foreach($find as $k => $regex ) 
            {
                // replaced
                if( isset($replaced[$k]) && !empty($replaced[$k]) ) 
                {
                   $link = str_ireplace($regex, $replaced[$k], $link);
                }
            }
        }
        
        // add home api url
        if( !empty($apiUrl) ) {
            $apiUrl .= (substr($apiUrl, -1) != '/') ? '/' : '';
            if($prefixApi) $apiUrl .= self::$prefixApiUrl;
            $link = $apiUrl . $link;
        }
        
        return $link;
        
    }
    
    /**
     * 
     * Method created link for view
     * 
     * @param  string   $slug, slug for a tour of json
     * @param  array    $view, views component to search
     * @param  array    $search, gluing GET parameters search
     * @param  string   $otherParamsLink, first elem text constant in .ini file, other arguments if sprintf
     * 
     * @return string
     * 
     * @since  1.0.0
     * 
     */
    static function createLink( $slug = '', $view = array('showcase'), $search = array(), $otherParamsLink = '' ) {
        
        $url = '';
        
        if( is_array($view) && !empty($view) ) {
            
            $menu = JFactory::getApplication()->getMenu();
            
            $tmpParent = '';
            if( is_array($search) && count($search) == 2 ) {
                $firstKey  = '';
                $secondKey = '';
                list($firstKey, $secondKey) = $search;
                $tmpParent = '&'.$firstKey.'='.$secondKey; 
            }
            
            $itemMenu = $menu->getItems('link', 'index.php?option=com_atoms&view='.$view[0].$tmpParent, true);
            $itemid = '';
            if( !empty($itemMenu) ) 
            {
                $itemid = '&Itemid='.$itemMenu->id; 
            }
            // search item id current active
            else if( isset($menu->getActive()->id) )
            {
                $itemid = '&Itemid='.$menu->getActive()->id;
            }
            
            $tmpSlug = '';
            if( $slug != '' ) {
                $tmpSlug = '&tour='.$slug; 
            }
            
            $url = JRoute::_('index.php?option=com_atoms&view='.((isset($view[1]))?$view[1]:$view[0]).$itemid.$tmpSlug.$otherParamsLink, false);
            
        } 
        
        return $url;
        
    } 

    /**
     * 
     * Method created link for payment tour
     * 
     * @param  string   $url, url api for json
     * @param  array    $find, replacement search options
     * @param  array    $replaced, replaced parameters in find
     * @param  string   $packageConstructor, other params of GET
     * 
     * @return string
     * 
     * @since  1.0.0
     * 
     */
    static function buildPaymentUrl( $url = '', $find = array(), $replaced = array(), $packageConstructor = '' ) {
        
        // replaced
        if( !empty($url) && !empty($find) ) 
        {
            // perse and build link
            foreach($find as $k => $regex ) 
            {
                // replaced
                if( isset($replaced[$k]) && !empty($replaced[$k]) ) 
                {
                   $url = str_ireplace($regex, $replaced[$k], $url);
                }
            }
        }
        
        // get params
        if( !empty($packageConstructor) ) 
        {
            $url = str_ireplace("{package_constructor}", "package_constructor?", $url);
            $url .= $packageConstructor;
        }
        else
        {
            $url = str_ireplace("{package_constructor}", "", $url);
        }
        
        return $url;
        
    }

}