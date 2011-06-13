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
 * Description for field shortTeaser
 * @global array $GLOBALS['TL_LANG']['tl_module']['shortTeaser'] 
 * @name $TL_LANG['tl_module']['shortTeaser']
 */
$GLOBALS['TL_LANG']['tl_module']['shortTeaser']
    = array(
            'Short Teaser',
            'Teaser automatisch aus einer Quelle füllen und kürzen.'
            );

/**
 * Description for field shortTeaserSource
 * @global array $GLOBALS['TL_LANG']['tl_module']['shortTeaserSource'] 
 * @name $TL_LANG['tl_module']['shortTeaserSource']
 */
$GLOBALS['TL_LANG']['tl_module']['shortTeaserSource']
    = array(
            'Teaser Quelle',
            'Die Quelle des Vorschautextes.'
            );

/**
 * Description for field shortTeaserShorten
 * @global array $GLOBALS['TL_LANG']['tl_module']['shortTeaserShorten'] 
 * @name $TL_LANG['tl_module']['shortTeaserShorten']
 */
$GLOBALS['TL_LANG']['tl_module']['shortTeaserShorten']
    = array(
            'Kürze Teaser',
            'Wie soll der Vorschautext gekürzt werden?'
           .' Leer lassen um die Kürzung zu deaktivieren.'
            );

$GLOBALS['TL_LANG']['tl_module']['shortTeaserShortenOptions']
    = array(
            'chars'      => 'Zeichen',
            'words'      => 'Wörter',
            'sentences'  => 'Sätze',
            'paragraphs' => 'Absätze',
            'sections'   => 'Kapitel'
    );

$GLOBALS['TL_LANG']['tl_module']['shortTeaserSourceOptions']
    = array(
            'teaser'         => 'Teaser',
            'full'           => 'Haupttext',
            'teaser_or_full' => 'Teaser wenn vorhanden, sonst Haupttext',
            'full_no_teaser' => 'Haupttext, wenn kein Teaser vorhanden ist'
    );
?>