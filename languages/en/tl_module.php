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
            'Auto fill teaser with text of distinct length and source?'
            );

/**
 * Description for field shortTeaserSource
 * @global array $GLOBALS['TL_LANG']['tl_module']['shortTeaserSource'] 
 * @name $TL_LANG['tl_module']['shortTeaserSource']
 */
$GLOBALS['TL_LANG']['tl_module']['shortTeaserSource']
    = array(
            'Teaser Source',
            'Select the source the teaser should be filled with.'
            );

/**
 * Description for field shortTeaserShorten
 * @global array $GLOBALS['TL_LANG']['tl_module']['shortTeaserShorten'] 
 * @name $TL_LANG['tl_module']['shortTeaserShorten']
 */
$GLOBALS['TL_LANG']['tl_module']['shortTeaserShorten']
    = array(
            'Shorten Teaser',
            'Choose how the teaser should be shortened. Leave empty to refuse shortening.'
            );

$GLOBALS['TL_LANG']['tl_module']['shortTeaserShortenOptions']
    = array(
            'chars'      => 'Characters',
            'words'      => 'Words',
            'sentences'  => 'Sentences',
            'paragraphs' => 'Paragraphs',
            'sections'   => 'Sections'
    );

$GLOBALS['TL_LANG']['tl_module']['shortTeaserSourceOptions']
    = array(
            'teaser'         => 'Teaser',
            'full'           => 'Fulltext',
            'teaser_or_full' => 'Teaser if present, Fulltext otherwise',
            'full_no_teaser' => 'Fulltext if no Teaser present'
    );
?>