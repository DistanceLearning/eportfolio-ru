<?php
/**
 *
 * @package    mahara
 * @subpackage artefact-tests
 * @author     Catalyst IT Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL version 3 or later
 * @copyright  For copyright information on Mahara, please see the README file distributed with this software.
 *
 */

define('INTERNAL', true);
define('MENUITEM', 'content/tests');

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/init.php');
require_once('pieforms/pieform.php');
require_once('pieforms/pieform/elements/calendar.php');
require_once(get_config('docroot') . 'artefact/lib.php');
safe_require('artefact','tests');

if (!PluginArtefacttests::is_active()) {
    throw new AccessDeniedException(get_string('plugindisableduser', 'mahara', get_string('tests','artefact.tests')));
}

define('TITLE', get_string('edittestask','artefact.tests'));

$id = param_integer('id');
$testask = new ArtefactTypeTestestask($id);
if (!$USER->can_edit_artefact($testask)) {
    throw new AccessDeniedException(get_string('accessdenied', 'error'));
}

$form = ArtefactTypeTestestask::get_form($testask->get('parent'), $testask);

$smarty = smarty();
$smarty->assign('editform', $form);
$smarty->assign('PAGEHEADING', hsc(get_string("editingtestask", "artefact.tests")));
$smarty->display('artefact:tests:edit.tpl');
