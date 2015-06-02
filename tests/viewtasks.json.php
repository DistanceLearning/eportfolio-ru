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
define('JSON', 1);

require(dirname(dirname(dirname(__FILE__))) . '/init.php');
safe_require('artefact', 'tests');
require_once(get_config('docroot') . 'blocktype/lib.php');
require_once(get_config('docroot') . 'artefact/tests/blocktype/tests/lib.php');

$offset = param_integer('offset', 0);
$limit = param_integer('limit', 10);

if ($blockid = param_integer('block', null)) {
    $bi = new BlockInstance($blockid);
    if (!can_view_view($bi->get('view'))) {
        json_reply(true, get_string('accessdenied', 'error'));
    }
    $options = $configdata = $bi->get('configdata');

    $testasks = ArtefactTypeTestestask::get_testasks($configdata['artefactid'], $offset, $limit);

    $template = 'artefact:tests:testaskrows.tpl';
    $baseurl = $bi->get_view()->get_url();
    $baseurl .= ((false === strpos($baseurl, '?')) ? '?' : '&') . 'block=' . $blockid;
    $pagination = array(
        'baseurl'   => $baseurl,
        'id'        => 'block' . $blockid . '_pagination',
        'datatable' => 'testasktable_' . $blockid,
        'jsonscript' => 'artefact/tests/viewtestasks.json.php',
    );
}
else {
    $testid = param_integer('artefact');
    $viewid = param_integer('view');
    if (!can_view_view($viewid)) {
        json_reply(true, get_string('accessdenied', 'error'));
    }
    $options = array('viewid' => $viewid);
    $testasks = ArtefactTypeTestestask::get_testasks($testid, $offset, $limit);

    $template = 'artefact:tests:testaskrows.tpl';
    $baseurl = get_config('wwwroot') . 'view/artefact.php?artefact=' . $testid . '&view=' . $options['viewid'];
    $pagination = array(
        'baseurl' => $baseurl,
        'id' => 'testask_pagination',
        'datatable' => 'testasktable',
        'jsonscript' => 'artefact/tests/viewtestasks.json.php',
    );

}
ArtefactTypeTestestask::render_testasks($testasks, $template, $options, $pagination);

json_reply(false, (object) array('message' => false, 'data' => $testasks));
