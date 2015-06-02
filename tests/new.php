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

define('INTERNAL', 1);
define('MENUITEM', 'content/tests');
define('SECTION_PLUGINTYPE', 'artefact');
define('SECTION_PLUGINNAME', 'tests');

require(dirname(dirname(dirname(__FILE__))) . '/init.php');
safe_require('artefact', 'tests');
if (!PluginArtefacttests::is_active()) {
    throw new AccessDeniedException(get_string('plugindisableduser', 'mahara', get_string('tests','artefact.tests')));
}

$id = param_integer('id',0);
if ($id) {
    $test = new ArtefactTypeTest($id);
    if (!$USER->can_edit_artefact($test)) {
        throw new AccessDeniedException(get_string('accessdenied', 'error'));
    }
    define('TITLE', get_string('newtestask','artefact.tests'));
    $form = ArtefactTypeTestestask::get_form($id);
}
else {
    define('TITLE', get_string('newtest','artefact.tests'));
    $form = ArtefactTypeTest::get_form();
}

$smarty =& smarty();
$smarty->assign_by_ref('form', $form);
$smarty->assign_by_ref('PAGEHEADING', hsc(TITLE));
$smarty->display('artefact:tests:new.tpl');
