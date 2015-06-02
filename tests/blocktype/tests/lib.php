<?php
/**
 *
 * @package    mahara
 * @subpackage blocktype-tests
 * @author     Catalyst IT Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL version 3 or later
 * @copyright  For copyright information on Mahara, please see the README file distributed with this software.
 *
 */

defined('INTERNAL') || die();

class PluginBlocktypetests extends PluginBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.tests/tests');
    }

    public static function get_description() {
        return get_string('description1', 'blocktype.tests/tests');
    }

    public static function get_categories() {
        return array('general');
    }

     /**
     * Optional method. If exists, allows this class to decide the title for
     * all blockinstances of this type
     */
    public static function get_instance_title(BlockInstance $bi) {
        $configdata = $bi->get('configdata');

        if (!empty($configdata['artefactid'])) {
            return $bi->get_artefact_instance($configdata['artefactid'])->get('title');
        }
        return '';
    }

    public static function get_instance_javascript(BlockInstance $bi) {
        $blockid = $bi->get('id');
        return array(
            array(
                'file'   => 'js/testsblock.js',
                'initjs' => "initNewtestsBlock($blockid);",
            )
        );
    }

    public static function render_instance(BlockInstance $instance, $editing=false) {
        global $exporter;

        require_once(get_config('docroot') . 'artefact/lib.php');
        safe_require('artefact','tests');

        $configdata = $instance->get('configdata');

        $smarty = smarty_core();
        if (isset($configdata['artefactid'])) {
            $test = artefact_instance_from_id($configdata['artefactid']);
            $testasks = ArtefactTypeTestestask::get_testasks($configdata['artefactid']);
            $template = 'artefact:tests:testaskrows.tpl';
            $blockid = $instance->get('id');
            if ($exporter) {
                $pagination = false;
            }
            else {
                $baseurl = $instance->get_view()->get_url();
                $baseurl .= ((false === strpos($baseurl, '?')) ? '?' : '&') . 'block=' . $blockid;
                $pagination = array(
                    'baseurl'   => $baseurl,
                    'id'        => 'block' . $blockid . '_pagination',
                    'datatable' => 'testasktable_' . $blockid,
                    'jsonscript' => 'artefact/tests/viewtestasks.json.php',
                );
            }
            ArtefactTypeTestestask::render_testasks($testasks, $template, $configdata, $pagination);

            if ($exporter && $testasks['count'] > $testasks['limit']) {
                $artefacturl = get_config('wwwroot') . 'view/artefact.php?artefact=' . $configdata['artefactid']
                    . '&view=' . $instance->get('view');
                $testasks['pagination'] = '<a href="' . $artefacturl . '">' . get_string('alltestasks', 'artefact.tests') . '</a>';
            }
            $smarty->assign('owner', $test->get('owner'));
            $smarty->assign('tags', $test->get('tags'));
            $smarty->assign('testasks', $testasks);
        }
        else {
            $smarty->assign('notests','blocktype.tests/tests');
        }
        $smarty->assign('blockid', $instance->get('id'));
        return $smarty->fetch('blocktype:tests:content.tpl');
    }

    // My tests blocktype only has 'title' option so next two functions return as normal
    public static function has_instance_config() {
        return true;
    }

    public static function instance_config_form($instance) {
        $instance->set('artefactplugin', 'tests');
        $configdata = $instance->get('configdata');

        $form = array();

        // Which resume field does the user want
        $form[] = self::artefactchooser_element((isset($configdata['artefactid'])) ? $configdata['artefactid'] : null);

        return $form;
    }

    public static function artefactchooser_element($default=null) {
        safe_require('artefact', 'tests');
        return array(
            'name'  => 'artefactid',
            'type'  => 'artefactchooser',
            'title' => get_string('teststoshow', 'blocktype.tests/tests'),
            'defaultvalue' => $default,
            'blocktype' => 'tests',
            'selectone' => true,
            'search'    => false,
            'artefacttypes' => array('test'),
            'template'  => 'artefact:tests:artefactchooser-element.tpl',
        );
    }

    public static function allowed_in_view(View $view) {
        return $view->get('owner') != null;
    }
}
