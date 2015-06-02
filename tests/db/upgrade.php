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

defined('INTERNAL') || die();

function xmldb_artefact_tests_upgrade($oldversion=0) {

    if ($oldversion < 2010072302) {
        set_field('artefact', 'container', 1, 'artefacttype', 'test');
    }

    return true;
}
