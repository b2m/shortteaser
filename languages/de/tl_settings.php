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
 * Heading for shortTeaserHTML
 * @global array $GLOBALS['TL_LANG']['tl_settings']['shortTeaser_legend'] 
 * @name $TL_LANG['tl_settings']['shortTeaser_legend']
 */
$GLOBALS['TL_LANG']['tl_settings']['shortTeaser_legend']
    = 'Short Teaser Einstellungen';

/**
 * Description for setting field shortTeaserHTML
 * @global array $GLOBALS['TL_LANG']['tl_settings']['shortTeaserHTML'] 
 * @name $TL_LANG['tl_settings']['shortTeaserHTML']
 */
$GLOBALS['TL_LANG']['tl_settings']['shortTeaserHTMLlabel']
    = array(
            'Erlaubte HTML Tags im Vorschautext',
            'Hier sind die erlaubten HTML Tags für Vorschautexte einzutragen.'
           .' Alle anderen Tags werden entfernt.'
            );
?>