<?php
/**
 * Short Teaser
 *
 * PHP version 5
 *
 * @category  Contao
 * @package   ShortTeaser
 * @author    Benjamin Meier <gpl@code-meier.de>
 * @copyright 2011 Benjamin Meier
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link      http://www.code-meier.de
 */

if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Add palettes to tl_settings
 * @global array $GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] 
 * @name $palettes['default']
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default']
    = str_replace(
        ';{search_legend:hide}',
        ';{shortTeaser_legend:hide},shortTeaserHTML,shortTeaserDebug;{search_legend:hide}',
        $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']
    );

/**
 * Add allowed HTML field to tl_settings
 * @global array $GLOBALS['TL_DCA']['tl_settings']['fields']['shortTeaserHTML'] 
 * @name $fields['shortTeaserHTML']
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['shortTeaserHTML']
    = array(
            'label'     => &$GLOBALS['TL_LANG']['tl_settings']['shortTeaserHTMLlabel'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => '<a><abbr><acronym><b><basefont><bdo><big><br><button><cite><code><del><dfn><em><font><i><img><ins><input><kbd><label><map><q><samp><small><span><strong><sub><sup><tt><var>',
            'eval'      => array('preserveTags'    => true,
                                 'tl_class'        => 'long',
                                 'nospace'         => true,
                                 'mandatory'       => true)
    );
/**
 * Add debug field to tl_settings
 * @global array $GLOBALS['TL_DCA']['tl_settings']['fields']['shortTeaserDebug'] 
 * @name $fields['shortTeaserHTML']
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['shortTeaserDebug']
    = array(
            'label'     => &$GLOBALS['TL_LANG']['tl_settings']['shortTeaserDebuglabel'],
            'exclude'   => true,
            'inputType' => 'checkbox'
    );
?>