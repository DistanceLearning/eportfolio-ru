<?php
/**
 *
 * @package    mahara
 * @subpackage artefact-tests-export-leap
 * @author     Catalyst IT Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL version 3 or later
 * @copyright  For copyright information on Mahara, please see the README file distributed with this software.
 *
 */

defined('INTERNAL') || die();

class LeapExportElementtest extends LeapExportElement {

    public function get_leap_type() {
        return 'test';
    }

    public function get_template_path() {
        return 'export:leap/tests:test.tpl';
    }
}

class LeapExportElementtestask extends LeapExportElementtest {

    public function assign_smarty_vars() {
        parent::assign_smarty_vars();
        $this->smarty->assign('completion', $this->artefact->get('completed') ? 'completed' : 'testned');
    }

    public function get_dates() {
        return array(
            array(
                'point' => 'target',
                'date'  => format_date($this->artefact->get('completiondate'), 'strftimew3cdate'),
            ),
        );
    }
}
