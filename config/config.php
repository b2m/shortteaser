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
 * Add InsertTag
 * @global array $GLOBALS['TL_HOOKS']['parseArticles']['ShortTeaser']['shortNewsTeaser']
 * @name $TL_HOOKS['replaceInsertTags']['ShortTeaser']['shortNewsTeaser']
 */
$GLOBALS['TL_HOOKS']['parseArticles'][] = array('ShortTeaser', 'shortNewsTeaser');

?>
