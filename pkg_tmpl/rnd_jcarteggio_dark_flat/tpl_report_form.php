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

class jtt_tpl_report_form extends JoomlaTuneTemplate
{
	function render() 
	{
?>
<h4><?php echo JText::_('REPORT_TO_ADMINISTRATOR'); ?></h4>
<form class="rnd-form rnd-margin" id="comments-report-form" name="comments-report-form" action="javascript:void(null);">
<?php
		if ($this->getVar('isGuest', 1) == 1) {
?>
<div class="rnd-form-row">
	<label for="comments-report-form-name"><?php echo JText::_('REPORT_NAME'); ?></label>
	<div class="rnd-form-controls">
		<input id="comments-report-form-name" type="text" name="name" value="" maxlength="255" size="22" />
	</div>
</div>
<?php
		}
?>
<div class="rnd-form-row">
	<label for="comments-report-form-reason"><?php echo JText::_('REPORT_REASON'); ?></label>
	<div class="rnd-form-controls">
		<input id="comments-report-form-reason" type="text" name="reason" value="" maxlength="255" size="22" />
	</div>
</div>

<div class="rnd-form-row" id="comments-report-form-buttons">
	<div class="rnd-form-controls" data-rnd-margin>
		<a class="rnd-button rnd-button-small rnd-button-primary" href="#" onclick="jcomments.saveReport();return false;" title="<?php echo JText::_('REPORT_SUBMIT'); ?>"><?php echo JText::_('REPORT_SUBMIT'); ?></a>
		<a class="rnd-button rnd-button-small rnd-button-danger" href="#" onclick="jcomments.cancelReport();return false;" title="<?php echo JText::_('REPORT_CANCEL'); ?>"><?php echo JText::_('REPORT_CANCEL'); ?></a>
	</div>
</div>
<input type="hidden" name="commentid" value="<?php echo $this->getVar('comment-id'); ?>" />
</form>
<?php
	}
}