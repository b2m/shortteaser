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
 * Add subpalette to tl_module
 * @global array $GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__']['shortTeaser'] 
 * @name $palettes['__selector__']['shortTeaser']
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'shortTeaser';

/**
 * Add subpalette to tl_module
 * @global array $GLOBALS['TL_DCA']['tl_module']['subpalettes']['shortTeaser'] 
 * @name $subpalettes['shortTeaser']
 */
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['shortTeaser'] = 'shortTeaserSource,shortTeaserShorten';

/**
 * Add palettes to tl_module
 * @global array $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist'] 
 * @name $palettes['newslist']
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']
    = str_replace(
        ',skipFirst;',
        ',skipFirst,shortTeaser;',
        $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']
    );

/**
 * Add shortTeaser field to tl_module
 * @global array $GLOBALS['TL_DCA']['tl_module']['fields']['shortTeaser'] 
 * @name $fields['shortTeaser']
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['shortTeaser']
    = array(
            'label'     => &$GLOBALS['TL_LANG']['tl_module']['shortTeaser'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array('submitOnChange'=>true)
    );

/**
 * Add source field to tl_module
 * @global array $GLOBALS['TL_DCA']['tl_module']['fields']['shortTeaserSource'] 
 * @name $fields['shortTeaserSource']
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['shortTeaserSource']
    = array(
            'label'     => &$GLOBALS['TL_LANG']['tl_module']['shortTeaserSource'],
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => array('teaser',
                                 'full',
                                 'teaser_or_full',
                                 'full_no_teaser'),
            'reference' => &$GLOBALS['TL_LANG']['tl_module']['shortTeaserSourceOptions'],
            'eval'      => array('tl_class'=>'w50')
    );

/**
 * Add shorten field to tl_module
 * @global array $GLOBALS['TL_DCA']['tl_module']['fields']['shortTeaserShorten'] 
 * @name $fields['shortTeaserShorten']
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['shortTeaserShorten']
    = array(
            'label'     => &$GLOBALS['TL_LANG']['tl_module']['shortTeaserShorten'],
            'exclude'   => true,
            'inputType' => 'inputUnit',
            'options'   => array('chars',
                                 'words',
                                 'sentences',
                                 'paragraphs',
                                 'sections'),
            'reference' => &$GLOBALS['TL_LANG']['tl_module']['shortTeaserShortenOptions'],
            'eval'      => array('maxlength'=>4,
                                 'rgxp'=>'digit',
                                 'nospace'=>true,
                                 'tl_class'=>'w50')
    );