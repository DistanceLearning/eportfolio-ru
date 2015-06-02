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
safe_require('artefact','tests');

define('TITLE', get_string('deletetest','artefact.tests'));

$id = param_integer('id');
$todelete = new ArtefactTypeTest($id);
if (!$USER->can_edit_artefact($todelete)) {
    throw new AccessDeniedException(get_string('accessdenied', 'error'));
}

$deleteform = array(
    'name' => 'deletetestform',
    'plugintype' => 'artefact',
    'pluginname' => 'test',
    'renderer' => 'div',
    'elements' => array(
        'submit' => array(
            'type' => 'submitcancel',
            'value' => array(get_string('deletetest','artefact.tests'), get_string('cancel')),
            'goto' => get_config('wwwroot') . '/artefact/tests/index.php',
        ),
    )
);
$form = pieform($deleteform);

$smarty = smarty();
$smarty->assign('form', $form);
$smarty->assign('PAGEHEADING', $todelete->get('title'));
$smarty->assign('subheading', get_string('deletethistest','artefact.tests',$todelete->get('title')));
$smarty->assign('message', get_string('deletetestconfirm','artefact.tests'));
$smarty->display('artefact:tests:delete.tpl');

// calls this function first so that we can get the artefact and call delete on it
function deletetestform_submit(Pieform $form, $values) {
    global $SESSION, $todelete;

    $todelete->delete();
    $SESSION->add_ok_msg(get_string('testdeletedsuccessfully', 'artefact.tests'));

    redirect('/artefact/tests/index.php');
}
