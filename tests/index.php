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
define('SECTION_PAGE', 'index');

require(dirname(dirname(dirname(__FILE__))) . '/init.php');
safe_require('artefact', 'tests');

define('TITLE', get_string('tests','artefact.tests'));

if (!PluginArtefacttests::is_active()) {
    throw new AccessDeniedException(get_string('plugindisableduser', 'mahara', get_string('tests','artefact.tests')));
}

// offset and limit for pagination
$offset = param_integer('offset', 0);
$limit  = param_integer('limit', 10);

$tests = ArtefactTypeTest::get_tests($offset, $limit);
ArtefactTypeTest::build_tests_list_html($tests);

$js = <<< EOF
addLoadEvent(function () {
    {$tests['pagination_js']}
});
EOF;

$smarty = smarty(array('paginator'));
$smarty->assign_by_ref('tests', $tests);
$smarty->assign('strnotestsaddone',
    get_string('notestsaddone', 'artefact.tests',
    '<a href="' . get_config('wwwroot') . 'artefact/tests/new.php">', '</a>'));
$smarty->assign('PAGEHEADING', hsc(get_string("tests", "artefact.tests")));
$smarty->assign('INLINEJAVASCRIPT', $js);
$smarty->display('artefact:tests:index.tpl');
