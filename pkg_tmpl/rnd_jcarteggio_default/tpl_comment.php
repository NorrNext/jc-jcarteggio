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
 * Comment item template. Results of rendering used in tpl_list.php
 */
 
class jtt_tpl_comment extends JoomlaTuneTemplate
{
	function render()
	{
		$comment = $this->getVar('comment');

		if (isset($comment)) {
			if ($this->getVar('get_comment_vote', 0) == 1) {
				// return comment vote
			 	$this->getCommentVoteValue( $comment );
			} else if ($this->getVar('get_comment_body', 0) == 1) {
				// return only comment body (for example after quick edit)
				echo $comment->comment;
			} else {
				
		// return all comment item
		$comment_number = $this->getVar('comment-number', 1);
		//$thisurl = $this->getVar('thisurl', '');
		$thisulr = JURI::current();

		/* Check admin */
		$adminmark 	= '';
		$checkUser	= $this->getVar('comment')->userid;
		$user 		= JFactory::getUser($checkUser);
		$checkACL 	= $user->get('groups');
		if(is_array($checkACL)){
			$group = array_shift($checkACL);
			if($group === '7' || $group === '8'){
				$adminmark = ' rnd-comment-primary';
			}	
		}
		$avatarClass = ($this->getVar('avatar') == 1) ? 'avatars-enabled' : ''; 
		
?>

<!-- Start Comment -->
<article class="rnd-comment<?php echo $adminmark .' ' . $avatarClass; ?>">

<!-- Start Header Comment -->
<header class="rnd-comment-header">

	<?php if ($this->getVar('avatar') == 1) : ?>
	<div class="rnd-comment-avatar"><?php echo $comment->avatar; ?></div>
	<?php endif; ?>
	
	<?php if ($this->getVar('comment-show-vote', 0) == 1) : ?>	
	<!-- Vote -->
	<div class="rnd-float-right"><?php $this->getCommentVote( $comment ); ?></div>
	<?php endif; ?>
	
	<h4 class="rnd-comment-title">
		<?php if ($this->getVar('comment-show-homepage') == 1) : ?>
		<!-- Author with link -->
		<a class="author-homepage" href="<?php echo $comment->homepage; ?>" rel="nofollow" title="<?php echo $comment->author; ?>">
			<?php echo $comment->author; ?>
		</a>
		<?php else : ?>
		<!-- Author -->
		<span class="comment-author"><?php echo $comment->author?></span>
		<?php endif; ?>	
		<?php if (($this->getVar('comment-show-title') > 0) && ($comment->title != '')) : ?>
		<!-- Title -->
			  &mdash; <?php echo $comment->title; ?>
		<?php endif; ?>
	</h4>

	<!-- Start Meta data about comment -->
	<div class="rnd-comment-meta">
		<!-- Time -->
		<div class="comment-date-box">
			<i class="rnd-icon-clock"></i>
			<time class="comment-date" datetime="<?php echo JCommentsText::formatDate($comment->date, JText::_('DATETIME_FORMAT')); ?>">
				<?php echo JCommentsText::formatDate($comment->date, JText::_('DATETIME_FORMAT')); ?>
			</time>
		</div>
		<?php if (($this->getVar('comment-show-email') > 0) && ($comment->email != '')) : ?>
			<!-- Email -->
			<div class="comment-email-box">
				<a class="comment-email" href="mailto:<?php echo $comment->email; ?>">
					<i class="rnd-icon-mail"></i>
				</a>
			</div>
		<?php endif; ?>		
		<!-- Anchor -->
		<div class="comment-anchor-box">		
			<a class="comment-anchor" href="<?php echo $thisurl; ?>#comment-<?php echo $comment->id; ?>" id="comment-<?php echo $comment->id; ?>">
				<i class="rnd-icon-anchor rnd-margin-small-right"></i>
				<?php echo $comment_number; ?>
			</a>
		</div>
	</div>
	<!-- End Meta data about comment -->

</header>
<!-- End Header Comment -->

<div class="rnd-comment-body" id="comment-body-<?php echo $comment->id; ?>"><?php echo $comment->comment; ?></div>

<!-- Buttons -->
<?php if (($this->getVar('button-reply') == 1) || ($this->getVar('button-quote') == 1) || ($this->getVar('button-report') == 1)) : ?>
	<footer class="comments-buttons rnd-margin rnd-float-left" data-rnd-margin>
		<?php if ($this->getVar('button-reply') == 1) :?>
		<!-- Button Reply-->
			<a class="rnd-button rnd-button-mini" href="#" data-rnd-tooltip title="<?php echo JText::_('BUTTON_REPLY'); ?>" onclick="jcomments.showReply(<?php echo $comment->id; ?>); return false;"><i class="rnd-icon-comment rnd-margin-small-right"></i> <span class="rnd-hidden-small"><?php echo JText::_('BUTTON_REPLY'); ?></span></a>
			
			<?php if ($this->getVar('button-quote') == 1) : ?>
				<!-- Button Reply Quote-->
				<a class="rnd-button rnd-button-mini" data-rnd-tooltip title="<?php echo JText::_('BUTTON_REPLY_WITH_QUOTE'); ?>" href="#" onclick="jcomments.showReply(<?php echo $comment->id; ?>,1); return false;"><i class="rnd-icon-comments rnd-margin-small-right"></i> <span class="rnd-hidden-small"><?php echo JText::_('BUTTON_REPLY_WITH_QUOTE'); ?></span></a>
			<?php endif; ?>
			
		<?php endif; ?>
			
		<?php if ($this->getVar('button-quote') == 1) : ?>
			<!-- Button Quote-->
			<a class="rnd-button rnd-button-mini" href="#" data-rnd-tooltip title="<?php echo JText::_('BUTTON_QUOTE'); ?>" onclick="jcomments.quoteComment(<?php echo $comment->id; ?>); return false;"><i class="rnd-icon-quote-left rnd-margin-small-right"></i> <span class="rnd-hidden-small"><?php echo JText::_('BUTTON_QUOTE'); ?></span></a>
		<?php endif; ?>
		
		<?php if ($this->getVar('button-report') == 1) : ?>
			<!-- Button Report-->
			<a class="rnd-button rnd-button-mini" href="#" onclick="jcomments.reportComment(<?php echo $comment->id; ?>); return false;"><i class="rnd-icon-close"></i> <?php echo JText::_('BUTTON_REPORT'); ?></a>
		<?php endif; ?>
	
	</footer>
	<?php
		// show frontend moderation panel
		$this->getCommentAdministratorPanel( $comment );
	?>		
	<div class="rnd-clearfix"></div>
	
<?php endif; ?>

</article>
<!-- End Comment -->

<?php
			}
		}
	}

	/*
	 *
	 * Displays comment's administration panel
	 *
	 */
	function getCommentAdministratorPanel( &$comment )
	{
		if ($this->getVar('comments-panel-visible', 0) == 1) :
?>
<footer class="rnd-toolbar rnd-margin rnd-text-right" data-rnd-margin id="comment-toolbar-<?php echo $comment->id; ?>">
	
	<?php if ($this->getVar('button-edit') == 1) : ?>
	<!-- Edit -->
		<a class="rnd-button rnd-button-mini toolbar-button-edit" href="#" data-rnd-tooltip onclick="jcomments.editComment(<?php echo $comment->id; ?>); return false;" title="<?php echo JText::_('BUTTON_EDIT'); ?>">
			<i class="rnd-icon-edit"></i>
		</a>
	<?php endif; ?>
			
	<?php if ($this->getVar('button-delete') == 1) : ?>
	<!-- Delete -->
		<a class="rnd-button rnd-button-mini toolbar-button-delete" href="#" data-rnd-tooltip onclick="if (confirm('<?php echo JText::_('BUTTON_DELETE_CONIRM'); ?>')){jcomments.deleteComment(<?php echo $comment->id; ?>);}return false;" title="<?php echo JText::_('BUTTON_DELETE'); ?>">
			<i class="rnd-icon-trash"></i>
		</a>
	<?php endif; ?>
	
	<?php if ($this->getVar('button-publish') == 1) :
		$text = $comment->published ? JText::_('BUTTON_UNPUBLISH') : JText::_('BUTTON_PUBLISH');
		$class = $comment->published ? 'publish' : 'unpublish';
		$classicon = $comment->published ? 'toggle-on' : 'toggle-off';
	?>
	<!-- Publish / Unpublish -->
	<a class="rnd-button rnd-button-mini toolbar-button-<?php echo $class; ?>" href="#" data-rnd-tooltip onclick="jcomments.publishComment(<?php echo $comment->id; ?>);return false;" title="<?php echo $text; ?>">
		<i class="rnd-icon-<?php echo $classicon; ?>"></i>
	</a>
	<?php endif; ?>
	
	<?php
		if ($this->getVar('button-ip') == 1) :
			$text = JText::_('BUTTON_IP') . ' ' . $comment->ip;
	?>
	<a class="toolbar-button-ip rnd-button rnd-button-mini" href="#" data-rnd-tooltip onclick="jcomments.go('http://www.ripe.net/perl/whois?searchtext=<?php echo $comment->ip; ?>');return false;" title="<?php echo $text; ?>">
		<i class="rnd-icon-info-circle"></i>
	</a>
	<?php endif; ?>
	
	<?php
		if ($this->getVar('button-ban') == 1) :
			$text = JText::_('BUTTON_BANIP');
	?>
	<a class="toolbar-button-ban" href="#" onclick="jcomments.banIP(<?php echo $comment->id; ?>);return false;" title="<?php echo $text; ?>"></a>
	<?php endif; ?>
	
</footer><!-- end toolbar -->

<?php
		endif;
	}

	function getCommentVote( &$comment )
	{
		$value = intval($comment->isgood) - intval($comment->ispoor);

		if ($value == 0 && $this->getVar('button-vote', 0) == 0) {
			return;
		}
?>
<span class="comments-vote">

	<span id="comment-vote-holder-<?php echo $comment->id; ?>" class="rnd-vote-box">
	
		<?php echo  $this->getCommentVoteValue( $comment ); ?>
		<?php if ($this->getVar('button-vote', 0) == 1) : ?>
		<a href="#" class="vote-good rnd-text-success rnd-button rnd-button-mini" data-rnd-tooltip title="<?php echo JText::_('BUTTON_VOTE_GOOD'); ?>" onclick="jcomments.voteComment(<?php echo $comment->id;?>, 1);return false;">
			<i class="rnd-icon-thumbs-up"></i>
		</a>
		<a href="#" class="vote-poor rnd-text-danger rnd-button rnd-button-mini" data-rnd-tooltip title="<?php echo JText::_('BUTTON_VOTE_BAD'); ?>" onclick="jcomments.voteComment(<?php echo $comment->id;?>, -1);return false;">
			<i class="rnd-icon-thumbs-down"></i>
		</a>
		<?php endif; ?>

	</span>
	
</span>

<?php
	}

	function getCommentVoteValue( &$comment )
	{
		$value = intval($comment->isgood - $comment->ispoor);

		if ($value == 0 && $this->getVar('button-vote', 0) == 0 && $this->getVar('get_comment_vote', 0) == 0) {
			// if current value is 0 and user has no rights to vote - hide 0
			return;
		}

		if ($value < 0) {
			$class = 'poor rnd-text-danger';
		} else if ($value > 0) {
			$class = 'good rnd-text-success';
			$value = '+' . $value;
		} else {
			$class = 'none rnd-text-muted';
		}
?>
<span class="vote-<?php echo $class; ?>"><?php echo $value; ?></span>
<?php
	}
}