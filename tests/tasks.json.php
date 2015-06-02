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

$test = param_integer('id');
$limit = param_integer('limit', 10);
$offset = param_integer('offset', 0);

if (!$USER->can_edit_artefact(new ArtefactTypeTest($test))) {
    json_reply(true, get_string('accessdenied', 'error'));
}

$testasks = ArtefactTypeTestestask::get_testasks($test, $offset, $limit);
ArtefactTypeTestestask::build_testasks_list_html($testasks);

json_reply(false, (object) array('message' => false, 'data' => $testasks));
