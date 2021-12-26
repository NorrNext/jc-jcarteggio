<?php
/**
 * JComments - Joomla Comment System
 *
 * @version 3.0
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2013 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 */

/* 
 * @package    JComments
 * @theme      jCarteggio
 * @author     Shtier <support@roundtheme.com>
 * @copyright  Copyright (C) 2015 Roundtheme. All rights reserved. http://www.roundtheme.com/
 * @license    GNU General Public License version 3 or later; see license.txt
 */ 
 
defined('_JEXEC') or die;

/**
 * Threaded comments list template
 *
 */
class jtt_tpl_tree extends JoomlaTuneTemplate
{
	function render() 
	{
		$comments = $this->getVar('comments-items');

		if (isset($comments)) {
			$this->getHeader();
?>
<div class="rnd-comment-list" id="comments-lirnd-0">
<?php
			$i = 0;
			
			$count = count($comments);

			$currentLevel = 0;
		
			foreach($comments as $id => $comment) {
				$st_last_comments = ($i == ($count-1)) ? 'rnd-larnd-comment ' : '';
				$st_current_level_class = ($currentLevel < 6) ? 'rnd-level-'.$currentLevel.' ' : 'rnd-level-stop ';
				if ($currentLevel < $comment->level) {
					
?>
	</div>
	<div class="<?php echo $st_current_level_class; ?><?php echo $st_last_comments; ?>rnd-comment-list" id="comments-lirnd-<?php echo $comment->parent; ?>">
<?php				
				} else {
					$j = 0;
	
					if ($currentLevel >= $comment->level) {
						$j = $currentLevel - $comment->level;
					} else if ($comment->level > 0 && $i == $count - 1) {
						$j = $comment->level;
					}

					while($j > 0) {
?>
	</div>
<?php
						$j--;
					}
				}
?>
		<div class="<?php echo $st_last_comments; ?><?php echo ($i%2 ? 'odd' : 'even'); ?>" id="comment-item-<?php echo $id; ?>">
<?php
				echo $comment->html;

				if ($comment->children == 0) {
?>
		</div>
<?php
				}
				
				if ($comment->level > 0 && $i == $count - 1) {
					$j = $comment->level;
				}

				while($j > 0) {
?>
	</div>
<?php					$j--;
				}

				$i++;
				$currentLevel = $comment->level;
			}
?>
</div>
<div id="comments-lirnd-footer"><?php echo $this->getFooter();?></div>
<?php
		} else {
			// display single comment item (works when new comment is added)
			$comment = $this->getVar('comment-item');

			if (isset($comment)) {
				$i = $this->getVar('comment-modulo');
				$id = $this->getVar('comment-id');
?>
	<div class="<?php echo ($i%2 ? 'odd' : 'even'); ?>" id="comment-item-<?php echo $id; ?>"><?php echo $comment; ?></div>
<?php
			} else {
?>
<div class="comments-list" id="comments-lirnd-0"></div>
<?php
			}
		}

	}

	/*
	*
	* Display comments header and small buttons: rss and refresh
	*
	*/
	function getHeader()
	{
		$object_id = $this->getVar('comment-object_id');
		$object_group = $this->getVar('comment-object_group');

		$btnRSS = '';
		$btnRefresh = '';
		
		if ($this->getVar('comments-refresh', 1) == 1) {
			$btnRefresh = '<a class="rnd-button rnd-button-small rnd-margin-small-left" href="#" title="'.JText::_('BUTTON_REFRESH').'" data-rnd-tooltip onclick="jcomments.showPage('.$object_id.',\''. $object_group . '\',0);return false;"><i id="refresh-spin-head" class="rnd-icon-refresh"></i></a>';
		}

		if ($this->getVar('comments-rss') == 1) {
			$link = $this->getVar('rssurl');
			if (!empty($link)) {
				$btnRSS = '<a class="rnd-button rnd-button-small" href="'.$link.'" data-rnd-tooltip title="'.JText::_('BUTTON_RSS').'" target="_blank"><i class="rnd-icon-rss-square"></i></a>';
			}
		}
?>

<h4 class="rnd-clearfix"><span class="rnd-float-right"><?php echo $btnRSS; ?><?php echo $btnRefresh; ?></span><span class="rnd-comments-header"><?php echo JText::_('COMMENTS_LIST_HEADER'); ?></span></h4>
<?php
	}

	/*
	*
	* Display RSS feed and/or Refresh buttons after comments list
	*
	*/
	function getFooter()
	{
		$footer = '';

		$object_id = $this->getVar('comment-object_id');
		$object_group = $this->getVar('comment-object_group');

		$lines = array();

		if ($this->getVar('comments-refresh', 1) == 1) {
			$lines[] = '<a class="rnd-button rnd-button-small rnd-margin-small-left" href="#" title="'.JText::_('BUTTON_REFRESH').'" data-rnd-tooltip onclick="jcomments.showPage('.$object_id.',\''. $object_group . '\',0);return false;"><i id="refresh-spin" class="rnd-icon-refresh"></i></a>';
		}

		if ($this->getVar('comments-rss', 1) == 1) {
			$link = $this->getVar('rssurl');
			if (!empty($link)) {
				$lines[] = '<a class="rnd-button rnd-button-small rnd-margin-small-left" href="'.$link.'" data-rnd-tooltip title="'.JText::_('BUTTON_RSS').'" target="_blank"><i class="rnd-icon-rss-square"></i></a>';
			}
		}

		if ($this->getVar('comments-can-subscribe', 0) == 1) {
			$isSubscribed = $this->getVar('comments-user-subscribed', 0);

			$text = $isSubscribed ? JText::_('BUTTON_UNSUBSCRIBE') : JText::_('BUTTON_SUBSCRIBE');
			$func = $isSubscribed ? 'unsubscribe' : 'subscribe';

			$lines[] = '<a id="comments-subscription" class="subscribe rnd-button rnd-button-small rnd-margin-small-left" href="#" title="' . $text . '" onclick="jcomments.' . $func . '('.$object_id.',\''. $object_group . '\');return false;">'. $text .'</a>';
		}

		if (count($lines)) {
			$footer = '<div class="rnd-text-right" data-rnd-margin>'. implode('', $lines) . '</div>';
		}

		return $footer;
	}
}