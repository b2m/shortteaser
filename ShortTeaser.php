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
     * @use $GLOBALS['TL_CONFIG']['shortTeaserHTML']
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

        if ($mod===false || $mod->shortTeaser!=1) {
            return false;
        }
        
        $shortTeaserShorten = unserialize($mod->shortTeaserShorten);
        $type = $shortTeaserShorten['unit'];
        $count = $shortTeaserShorten['value'];

        $teaser = $objTemplate->teaser;
        $full = $objTemplate->text;
        $id = $objTemplate->id;
        
        switch ($mod->shortTeaserSource) {
        case 'teaser':
            $text = $this->checkHTML($teaser);
            break;
        case 'full':
            $text = $this->checkHTML($full);
            break;
        case 'teaser_or_full':
            if (strlen($teaser)>0) {
                $text = $this->checkHTML($teaser);
            } else {
                $text = $this->checkHTML($full);
            }
            break;
        case 'full_no_teaser':
            if (strlen($teaser)===0) {
                $text = $this->checkHTML($full);
            } else {
                $text = $teaser;
            }
            break;
        default:
            return false;
            break;
        }
        
        $new_text = $text;
        // check if shortening is activ
        if ($count>0) {
            switch ($type) {
            case 'chars':
                $new_text = $this->getChars($text, $count, $id);
                // add some chars like ...
                $new_text.= $GLOBALS['TL_LANG']['MSC']['shortTeaserEnd'];
                break;
            case 'words':
                $new_text = $this->getWords($text, $count, $id);
                // add some chars like ...
                $new_text.= $GLOBALS['TL_LANG']['MSC']['shortTeaserEnd'];
                break;
            case 'sentences':
                $new_text = $this->getSentences($text, $count, $id);
                break;
            case 'paragraphs':
                $new_text = $this->getParagraphs($text, $count, $id);
                break;
            case 'sections':
                $new_text = $this->getSections($text, $count, $id);
                break;
            default:
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
     *
     * @return string new teaser html code
     */
    protected function getChars($text, $count, $id)
    {
        // working copy of $count
        $real_count = $count;
        // working copy of $text
        $stext = $text;
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
                    $this->log($message, __CLASS__.' '.__METHOD__, TL_ERROR);
                } else {
                    // remove last corresponding opening tag
                    unset($tag_stack[count($tag_stack)-$res-1]);
                }
            } else if ($cm > 3) {
                // opening tag
                if ($matches[0][0][-2] != '/') {
                    // add opening tags to $tag_stack
                    array_push($tag_stack, $matches[3][0]);
                }
            } else {
                $message  = 'You may inform the developer of the module';
                $message .= ' '.__CLASS__.' that error 42 occured.';
                $message .= ' But this will never happen =)';
                $this->log($message, __CLASS__.' '.__METHOD__, TL_ERROR);
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
        // now cut real text
        $text = substr($text, 0, $real_count);
        // add missing closing tags in reverse order
        foreach (array_reverse($tag_stack) as $tag) {
            $text .= '</'.$tag.'>';
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
        // now use getChars()
        return $this->getChars($text, $pos, $id);
    }
}

?>