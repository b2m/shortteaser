<?php
/**
 * Short Teaser
 *
 * PHP version 5
 *
 * @category  Contao
 * @package   ShortTeaser
 * @author    Benjamin Meier <gpl@code-meier.de>
 * @copyright 2013 Benjamin Meier
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link      http://www.code-meier.de
 */

if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Heading for shortTeaserHTML
 * @global array $GLOBALS['TL_LANG']['tl_settings']['shortTeaser_legend'] 
 * @name $TL_LANG['tl_settings']['shortTeaser_legend']
 */
$GLOBALS['TL_LANG']['tl_settings']['shortTeaser_legend']
    = 'Short Teaser Settings';

/**
 * Description for setting field shortTeaserHTML
 * @global array $GLOBALS['TL_LANG']['tl_settings']['shortTeaserHTML'] 
 * @name $TL_LANG['tl_settings']['shortTeaserHTML']
 */
$GLOBALS['TL_LANG']['tl_settings']['shortTeaserHTMLlabel']
    = array(
            'Allowed HTML Tags for Teasers',
            'Enter HTML Tags that should be allowed in teaser texts.'
            .' All other tags will be removed.'
            );

/**
 * Description for setting field shortTeaserDebug
 * @global array $GLOBALS['TL_LANG']['tl_settings']['shortTeaserDebuglabel'] 
 * @name $TL_LANG['tl_settings']['shortTeaserDebuglabel']
 */
$GLOBALS['TL_LANG']['tl_settings']['shortTeaserDebuglabel']
    = array(
            'Activate Debugging',
            'Activates further messages in System Log'
            );
?>