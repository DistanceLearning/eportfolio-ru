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
define('SECTION_PAGE', 'tests');

require(dirname(dirname(dirname(__FILE__))) . '/init.php');
safe_require('artefact', 'tests');
if (!PluginArtefacttests::is_active()) {
    throw new AccessDeniedException(get_string('plugindisableduser', 'mahara', get_string('tests','artefact.tests')));
}

define('TITLE', get_string('testasks','artefact.tests'));

$id = param_integer('id');

// offset and limit for pagination
$offset = param_integer('offset', 0);
$limit  = param_integer('limit', 10);

$test = new ArtefactTypeTest($id);
if (!$USER->can_edit_artefact($test)) {
    throw new AccessDeniedException(get_string('accessdenied', 'error'));
}


$testasks = ArtefactTypeTestestask::get_testasks($test->get('id'), $offset, $limit);
ArtefactTypeTestestask::build_testasks_list_html($testasks);

$js = <<< EOF
addLoadEvent(function () {
    {$testasks['pagination_js']}
});
EOF;

$smarty = smarty(array('paginator'));
$smarty->assign_by_ref('testasks', $testasks);
$smarty->assign_by_ref('test', $id);
$smarty->assign_by_ref('tags', $test->get('tags'));
$smarty->assign_by_ref('owner', $test->get('owner'));
$smarty->assign('strnotestasksaddone',
    get_string('notestasksaddone', 'artefact.tests',
    '<a href="' . get_config('wwwroot') . 'artefact/tests/new.php?id='.$test->get('id').'">', '</a>'));
$smarty->assign('teststestasksdescription', get_string('teststestasksdescription', 'artefact.tests', get_string('newtestask', 'artefact.tests')));
$smarty->assign('PAGEHEADING', get_string("teststestasks", "artefact.tests",$test->get('title')));
$smarty->assign('INLINEJAVASCRIPT', $js);
$smarty->display('artefact:tests:test.tpl');
