<?php
/**
 *
 * @package    mahara
 * @subpackage artefact-tests-export-html
 * @author     Catalyst IT Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL version 3 or later
 * @copyright  For copyright information on Mahara, please see the README file distributed with this software.
 *
 */

defined('INTERNAL') || die();

class HtmlExporttests extends HtmlExportArtefactPlugin {

    public function pagination_data($artefact) {
        if ($artefact instanceof ArtefactTypeTest) {
            return array(
                'perpage'    => 10,
                'childcount' => $artefact->count_children(),
                'plural'     => get_string('tests', 'artefact.tests'),
            );
        }
    }

    public function dump_export_data() {
        foreach ($this->exporter->get('artefacts') as $artefact) {
            if ($artefact instanceof ArtefactTypeTest) {
                $this->paginate($artefact);
            }
        }
    }

    public function get_summary() {
        $smarty = $this->exporter->get_smarty();
        $tests = array();
        foreach ($this->exporter->get('artefacts') as $artefact) {
            if ($artefact instanceof ArtefactTypeTest) {
                $tests[] = array(
                    'link' => 'files/tests/' . PluginExportHtml::text_to_URLpath(PluginExportHtml::text_to_filename($artefact->get('title'))) . '/index.html',
                    'title' => $artefact->get('title'),
                );
            }
        }
        $smarty->assign('tests', $tests);

        return array(
            'title' => get_string('tests', 'artefact.tests'),
            'description' => $smarty->fetch('export:html/tests:summary.tpl'),
        );
    }

    public function get_summary_weight() {
        return 40;
    }
}
