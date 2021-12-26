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

defined('_JEXEC') or die;

/* 
 * @package    JComments
 * @theme      jCarteggio
 * @author     Shtier <support@roundtheme.com>
 * @copyright  Copyright (C) 2015 Roundtheme. All rights reserved. http://www.roundtheme.com/
 * @license    GNU General Public License version 3 or later; see license.txt
 */

/**
 * Flat comments list template
 */
class jtt_tpl_list extends JoomlaTuneTemplate
{
	function render() 
	{
		$comments = $this->getVar('comments-items');

		if (isset($comments)) {
			// display full comments list with navigation and other stuff
			$this->getHeader();

			if ($this->getVar('comments-nav-top') == 1) {
?>
<div id="nav-top" class="rnd-margin"><ul class="rnd-pagination"><?php echo $this->getNavigation(); ?></ul></div>
<?php
			}
?>
<div id="comments-list" class="comments-lirnd-flat">
<?php
			$i = 0;
			
			foreach($comments as $id => $comment) {
?>
	<div class="<?php echo ($i%2 ? 'odd' : 'even'); ?>" id="comment-item-<?php echo $id; ?>"><?php echo $comment; ?></div>
<?php
				$i++;
			}
?>
</div>
<?php
			if ($this->getVar('comments-nav-bottom') == 1) {
?>
<div id="nav-bottom" class="rnd-margin"><ul class="rnd-pagination"><?php echo $this->getNavigation(); ?></ul></div>
<?php
			}
?>
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
<div id="comments-list" class="comments-list"></div>
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
			$btnRefresh = '<a class="refresh rnd-button rnd-button-small rnd-margin-small-left" href="#" title="'.JText::_('BUTTON_REFRESH').'" data-rnd-tooltip onclick="jcomments.showPage('.$object_id.',\''. $object_group . '\',0);return false;"><i id="refresh-spin" class="rnd-icon-refresh"></i></a>';
		}

		if ($this->getVar('comments-rss') == 1) {
			$link = $this->getVar('rssurl');
			if (!empty($link)) {
				$btnRSS = '<a class="rss rnd-button rnd-button-small" href="'.$link.'" data-rnd-tooltip title="'.JText::_('BUTTON_RSS').'" target="_blank"><i class="rnd-icon-rss-square"></i></a>';
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

	/*
	 *
	 * Display comments pagination
	 *
	 */
	function getNavigation()
	{
		if ($this->getVar('comments-nav-top') == 1 
		||  $this->getVar('comments-nav-bottom') == 1) {
			$active_page = $this->getVar('comments-nav-active', 1);
			$first_page = $this->getVar('comments-nav-first', 0);
			$total_page = $this->getVar('comments-nav-total', 0);

			if ($first_page != 0 && $total_page != 0) {
				$object_id = $this->getVar('comment-object_id');
				$object_group = $this->getVar('comment-object_group');

				$content = '';

				// number of visible pages
				$pp = 10;

				$fp = $active_page - $pp/2;
				if ($fp <= 0) {
					$fp = 1;
				}

				$lp = $fp + $pp;
				if ($lp > $total_page) {
					$lp = $total_page;
				}

				if ($lp - $fp < $pp && $pp < $total_page) {
					$fp = $lp - $pp;
				}

				if ($fp > 1) {
					$content .= '<li><a href="" onclick="event.preventDefault(); jcomments.showPage('.$object_id.', \''.$object_group.'\', '.($active_page-1).');" class="page" onmouseover="this.className=\'hoverpage\';" onmouseout="this.className=\'page\';" >&laquo;</a></li>';
				}

				for ($i=$fp; $i <= $lp; $i++) {
					if ($i == $active_page) {
						$content .= '<li class="rnd-active"><span class="activepage">'.$i.'</span></li>';
					} else {
						$content .= '<li><a href=""  onclick="event.preventDefault(); jcomments.showPage('.$object_id.', \''.$object_group.'\', '.$i.');" class="page" onmouseover="this.className=\'hoverpage\';" onmouseout="this.className=\'page\';" >'.$i.'</a></li>';
					}
				}

				if ($lp < $total_page) {
					$content .= '<li><a href=""  onclick="event.preventDefault(); jcomments.showPage('.$object_id.', \''.$object_group.'\', '.($lp+1).');" class="page" onmouseover="this.className=\'hoverpage\';" onmouseout="this.className=\'page\';" >&raquo;</a></li>';
				}

				return $content;
			}
		}
		return '';
	}
}