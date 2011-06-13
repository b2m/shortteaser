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
 * Class ShortTeaser
 *
 * @category  Contao
 * @package   ShortTeaser
 * @author    Benjamin Meier <gpl@code-meier.de>
 * @copyright 2011 Benjamin Meier
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link      http://www.code-meier.de
 */
class ShortTeaser extends Frontend
{
    /**
     * Check if Teaser could be shortened
     *
     * Check if this is a newslist and if it
     * its teaser should be shortened
     *
     * @param object &$objTemplate containing template information
     * @param object $objArticles  containing newsdata
     *
     * @uses $GLOBALS['TL_CONFIG']['shortTeaserHTML']
     *
     * @return boolean
     */
    public function shortNewsTeaser(&$objTemplate, $objArticles)
    {
        $mod = false;
        foreach (debug_backtrace() as $b) {
            if ($b['class'] == 'ModuleNewsList') {
                $mod = $b['object'];
                 break;
            }
        }

        if ($mod===false || !is_object($mod) || $mod->shortTeaser!=1) {
            if ($mod===false) {
                $this->debug(
                    'No parent ModuleNewsList found!',
                    __METHOD__
                );
            } else if (!is_object($mod)) {
                $this->debug(
                    'No object found, we need PHP Version > 5.1.0!',
                    __METHOD__
                );
            } else {
                $this->debug(
                    'shortTeaser not active!',
                    __METHOD__
                );
            }
            return false;
        }
        $this->debug('Found ModuleNewsList and shortTeaser active.', __METHOD__);

        $shortTeaserShorten = unserialize($mod->shortTeaserShorten);
        $type = $shortTeaserShorten['unit'];
        $count = $shortTeaserShorten['value'];

        $teaser = $objTemplate->teaser;
        $full = $objTemplate->text;
        $id = $objTemplate->id;

        $text = "";

        switch ($mod->shortTeaserSource) {
        case 'teaser':
            $this->debug('Using teaser as source.', __METHOD__);
            $text = $this->checkHTML($teaser);
            break;
        case 'full':
            $this->debug('Using fulltext as source.', __METHOD__);
            $text = $this->checkHTML($full);
            break;
        case 'teaser_or_full':
            if (strlen($teaser)>0) {
                $this->debug('Using teaser as source.', __METHOD__);
                $text = $this->checkHTML($teaser);
            } else {
                $this->debug('Using fulltext as source.', __METHOD__);
                $text = $this->checkHTML($full);
            }
            break;
        case 'full_no_teaser':
            if (strlen($teaser)===0) {
                $this->debug('Using fulltext as source.', __METHOD__);
                $text = $this->checkHTML($full);
            } else {
                $this->debug('Using uncut teaser as source.', __METHOD__);
                $text = $teaser;
            }
            break;
        default:
            $this->debug(
                'Unknown value for shortTeaserSource!',
                __METHOD__, TL_ERROR
            );
            return false;
            break;
        }

        $new_text = $text;
        // check if shortening is activ
        if ($count>0) {
            switch ($type) {
            case 'chars':
                $this->debug('Short text to '.$count.' chars.', __METHOD__);
                $new_text = $this->getChars($text, $count, $id);
                // add some chars like ...
                $new_text.= $GLOBALS['TL_LANG']['MSC']['shortTeaserEnd'];
                break;
            case 'words':
                $this->debug('Short text to '.$count.' words.', __METHOD__);
                $new_text = $this->getWords($text, $count, $id);
                // add some chars like ...
                $new_text.= $GLOBALS['TL_LANG']['MSC']['shortTeaserEnd'];
                break;
            case 'sentences':
                $this->debug('Short text to '.$count.' sentences.', __METHOD__);
                $new_text = $this->getSentences($text, $count, $id);
                break;
            case 'paragraphs':
                $this->debug('Short text to '.$count.' paragraphs.', __METHOD__);
                $new_text = $this->getParagraphs($text, $count, $id);
                break;
            case 'sections':
                $this->debug('Short text to '.$count.' sections.', __METHOD__);
                $new_text = $this->getSections($text, $count, $id);
                break;
            default:
                $this->debug(
                    'Unknown value for shortTeaserShorten!',
                    __METHOD__, TL_ERROR
                );
                break;
            }
        }
        $objTemplate->teaser = $new_text;
        return true;
    }

    /**
     * Reduces some unallowed HTML
     *
     * Removes all HTML tags, not explicitely allowed
     * in global settings.
     *
     * @param string $text The text to be processed
     *
     * @return string Resulting html code.
     */
    protected function checkHTML($text)
    {
        return strip_tags($text, $GLOBALS['TL_CONFIG']['shortTeaserHTML']);
    }

    /**
     * Reduce text to characters
     *
     * Reduces the text $text to $count characters
     *
     * @param string $text  text to reduce
     * @param int    $count number of chars
     * @param int    $id    the id of the news item
     *
     * @uses $GLOBALS['TL_LANG']['MSC']['shortTeaserEnd']
     * @uses $GLOBALS['TL_CONFIG']['shortTeaserHTML']
     *
     * @return string new teaser html code
     */
    protected function getChars($text, $count, $id)
    {
        // working copy of $count
        $real_count = $count;
        // working copy of $text
        $stext = $text;
        // ignoring empty html elements
        $empty_tags = str_replace(
            array('<img>','<br>','<input>','<hr>'),
            '',
            $GLOBALS['TL_CONFIG']['shortTeaserHTML']
        );
        // tag stack to add missing closing tags
        $tag_stack = array();
        // internal counter for while
        $c = 0;
        // regular expression for html tags
        $reg = '#<(/([a-zA-Z0-9]*)|([A-Za-z0-9]*)( [^>]*)?)>#';
        // search all html tags and consider them
        while (preg_match($reg, $stext, $matches, PREG_OFFSET_CAPTURE)
               && $c < $real_count
               && ($c + $matches[0][1]) < $real_count
              ) {
            $cm = count($matches);
            if ($cm == 3) {
                // closing tag
                $res = array_search($matches[2][0], array_reverse($tag_stack));
                if ($res === false) {
                    $message  = 'Closing tag "'.htmlentities($$matches[2][0]).'"';
                    $message .= ' found in News with id '.$id.' but no';
                    $message .= ' corresponding opening tag before.';
                    $this->debug($message, __METHOD__, TL_ERROR);
                } else {
                    // remove last corresponding opening tag
                    $this->debug(
                        'Found closing tag '.$matches[2][0].'.',
                        __METHOD__
                    );
                    unset($tag_stack[count($tag_stack)-$res-1]);
                }
            } else if ($cm > 3) {
                // opening tag

                // remove empty tags
                $tag = strip_tags($matches[0][0], $empty_tags);
                if (strlen($tag)>0) {
                    // add opening tags to $tag_stack
                    array_push($tag_stack, $matches[3][0]);
                    $this->debug(
                        'Found opening tag '.$matches[3][0].'.',
                        __METHOD__
                    );
                } else {
                    $message = 'Found tag '.$matches[3][0]
                              .' but ignoring tag_stack.';
                    $this->debug(
                        $message,
                        __METHOD__
                    );
                }
            } else {
                $message  = 'You may inform the developer of the module';
                $message .= ' '.__CLASS__.' that error 42 occured.';
                $message .= ' But this will never happen =)';
                $this->debug($message, __METHOD__, TL_ERROR);
            }
            // get length of match
            $matchlength = strlen($matches[0][0]);
            $end_pos = $matchlength + $matches[0][1];
            // extend range
            $real_count += $matchlength;
            // extend actual position
            $c += $end_pos;
            // cut already checked text
            $stext = substr($stext, $end_pos);
        }
        if (strlen($text)<=$real_count) {
            $this->debug(
                'Nothing to short cause text has '.strlen($text)
                .' chars, '.$real_count.' allowed!',
                __METHOD__
            );
        } else {
            $this->debug(
                'Short Teaser to calculated '.$real_count.' chars.',
                __METHOD__
            );
        }
        // now cut real text
        $text = substr($text, 0, $real_count);
        // add missing closing tags in reverse order
        foreach (array_reverse($tag_stack) as $tag) {
            $text .= '</'.$tag.'>';
            $this->debug('Add missing closing tag '.$tag.'.', __METHOD__);
        }
        return $text;
    }

    /**
     * Reduce text to words
     *
     * Reduces the text $text to $count words
     *
     * @param string $text  text to reduce
     * @param int    $count number of words
     * @param int    $id    the id of the news item
     *
     * @uses getChars()
     *
     * @return string new teaser html code
     */
    protected function getWords($text, $count, $id)
    {
        // working copy of $text without html tags
        $stext = strip_tags($text);
        // get $count words from the beginning
        $words = explode(' ', $stext, $count+1);
        // check if there are some more words
        if (count($words)>$count) {
            // remove last element (containing the rest of the string)
            array_pop($words);
        }
        // calculate position of last word
        $pos = array_sum(array_map('strlen', $words))+count($words)-1;
        $this->debug(
            'Reducing to '.count($words).' words is equal to '.$pos.' chars.',
            __METHOD__
        );
        // now use getChars()
        return $this->getChars($text, $pos, $id);
    }

    /**
     * Reduce text to Sentences
     *
     * Reduces the text $text to $count sentences
     *
     * @param string $text  text to reduce
     * @param int    $count number of sentences
     * @param int    $id    the id of the news item
     *
     * @uses getChars()
     *
     * @return string new teaser html code
     */
    protected function getSentences($text, $count, $id)
    {
        // working copy of text without html tags
        $stext = strip_tags($text);
        // replace ! and ? with . for explode.
        $stext = str_replace(array('!', '?'), '.', $stext);
        $sentences = explode('.', $stext, $count+1);
        // check if there are some more sentences
        if (count($sentences)>$count) {
            // remove last element (containing the rest of the string)
            array_pop($sentences);
        }
        // calculate position of last sentence with .,? or ! at the end
        $pos = array_sum(array_map('strlen', $sentences))+count($sentences);
        $this->debug(
            'Reducing to '.count($sentences).' sentences'
            .' is equal to '.$pos.' chars.',
            __METHOD__
        );
        // now use getChars()
        return $this->getChars($text, $pos, $id);
    }

    /**
     * Reduce text to paragraphs
     *
     * Reduces the text $text to $count paragraphs
     *
     * @param string $text  text to reduce
     * @param int    $count number of paragraphs
     * @param int    $id    the id of the news item
     *
     * @uses getChars()
     *
     * @return string new teaser html code
     */
    protected function getParagraphs($text, $count, $id)
    {
        // working copy of text without html tags except p
        $stext = strip_tags($text, '<p>');
        $paragraphs = explode('</p>', $stext, $count+1);
        // check if there are some more paragraphs
        if (count($paragraphs)>$count) {
            // remove last element (containing the rest of the string)
            array_pop($paragraphs);
        }
        // remove html tags
        $paragraphs = array_map('strip_tags', $paragraphs);
        // calculate position of last paragraph
        $pos = array_sum(array_map('strlen', $paragraphs));
        $this->debug(
            'Reducing to '.count($paragraphs).' paragraphs'
            .' is equal to '.$pos.' chars.',
            __METHOD__
        );
        // now use getChars()
        return $this->getChars($text, $pos, $id);
    }

    /**
     * Reduce text to sections
     *
     * Reduces the text $text to $count sections
     *
     * @param string $text  text to reduce
     * @param int    $count number of sections
     * @param int    $id    the id of the news item
     *
     * @uses getChars()
     *
     * @return string new teaser html code
     */
    protected function getSections($text, $count, $id)
    {
        // headlines
        $hl = array();
        // search for type of first heading
        preg_match('#<h([1-6])[^>]*?>#', $text, $matches, PREG_OFFSET_CAPTURE);
        // search all html tags and consider them
        if (count($matches)==2) {
            $hl = $matches[1][0];
        } else {
            // no section found
            return $text;
        }

        // working copy of text
        $stext = $text;
        $sections = explode('</h'.$hl.'>', $stext, $count+2);
        // check if there are some more sections
        if (count($sections)>$count+1) {
            // remove last element (containing the rest of the string)
            array_pop($sections);
        }
        // check if heading of next section is present
        if (count($sections)>$count) {
            // remove heading of next section
            $ls = $sections[$count];
            $sections[$count] = substr($ls, 0, strrpos($ls, '<h'.$hl));
        }
        // remove html tags
        $sections = array_map('strip_tags', $sections);
        // calculate position of last section
        $pos = array_sum(array_map('strlen', $sections));
        $this->debug(
            'Reducing to '.count($sections).' sections is equal to '.$pos.' chars.',
            __METHOD__
        );
        // now use getChars()
        return $this->getChars($text, $pos, $id);
    }

    /**
     * Debug to system log
     *
     * Logs the debug message to the system log
     *
     * @param string $message text to log
     * @param string $method  caller method
     * @param string $type    type for logging message
     *
     * @uses $GLOBALS['TL_CONFIG']['shortTeaserDebug']
     *
     * @return boolean
     */
    protected function debug($message, $method, $type=TL_GENERAL)
    {
        if ($type==TL_ERROR || $GLOBALS['TL_CONFIG']['shortTeaserDebug']==1) {
            $this->log($message, __CLASS__.' '.$method, $type);
        }
    }
}

?>