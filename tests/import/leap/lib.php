<?php
/**
 *
 * @package    mahara
 * @subpackage artefact-tests-import-leap
 * @author     Catalyst IT Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL version 3 or later
 * @copyright  For copyright information on Mahara, please see the README file distributed with this software.
 *
 */

defined('INTERNAL') || die();

/**
 * Implements LEAP2A import of test/testask entries into Mahara
 *
 * Mahara currently only has two levels of test, but the exporting
 * system may have more, so the strategy will be to find all the tests
 * that are not part of another test, use those for the top level, with
 * everything else crammed in at the second level.
 */

class LeapImporttests extends LeapImportArtefactPlugin {

    const STRATEGY_IMPORT_AS_test = 1;

    // Keep track of test ancestors which will become testask parents
    private static $ancestors = array();
    private static $parents = array();

    public static function get_import_strategies_for_entry(SimpleXMLElement $entry, PluginImportLeap $importer) {
        $strategies = array();

        // Mahara can't handle html tests yet, so don't claim to be able to import them.
        if (PluginImportLeap::is_rdf_type($entry, $importer, 'test')
            && (empty($entry->content['type']) || (string)$entry->content['type'] == 'text')) {
            $strategies[] = array(
                'strategy' => self::STRATEGY_IMPORT_AS_test,
                'score'    => 90,
                'other_required_entries' => array(),
            );
        }

        return $strategies;
    }

    public static function add_import_entry_request_using_strategy(SimpleXMLElement $entry, PluginImportLeap $importer, $strategy, array $otherentries) {
        if ($strategy != self::STRATEGY_IMPORT_AS_test) {
            throw new ImportException($importer, 'TODO: get_string: unknown strategy chosen for importing entry');
        }
        self::add_import_entry_request_test($entry, $importer);
    }

/**
 * Import from entry requests for Mahara tests and their testasks
 *
 * @param PluginImportLeap $importer
 * @return updated DB
 * @throw    ImportException
 */
    public static function import_from_requests(PluginImportLeap $importer) {
        $importid = $importer->get('importertransport')->get('importid');
        if ($entry_requests = get_records_select_array('import_entry_requests', 'importid = ? AND plugin = ? AND entrytype = ?', array($importid, 'tests', 'test'))) {
            foreach ($entry_requests as $entry_request) {
                if ($testid = self::create_artefact_from_request($importer, $entry_request)) {
                    if ($testtestask_requests = get_records_select_array('import_entry_requests', 'importid = ? AND entryparent = ? AND entrytype = ?', array($importid, $entry_request->entryid, 'testask'))) {
                        foreach ($testtestask_requests as $testtestask_request) {
                            self::create_artefact_from_request($importer, $testtestask_request, $testid);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param SimpleXMLElement $entry
     * @param PluginImportLeap $importer
     * @param unknown_type $strategy
     * @param array $otherentries
     * @throws ImportException
     */
    public static function import_using_strategy(SimpleXMLElement $entry, PluginImportLeap $importer, $strategy, array $otherentries) {

        if ($strategy != self::STRATEGY_IMPORT_AS_test) {
            throw new ImportException($importer, 'TODO: get_string: unknown strategy chosen for importing entry');
        }

        $artefactmapping = array();
        $artefactmapping[(string)$entry->id] = self::create_test($entry, $importer);
        return $artefactmapping;
    }

    /**
     * Get the id of the test entry which ultimately contains this entry
     */
    public static function get_ancestor_entryid(SimpleXMLElement $entry, PluginImportLeap $importer) {
        $entryid = (string)$entry->id;

        if (!isset(self::$ancestors[$entryid])) {
            self::$ancestors[$entryid] = null;
            $child = $entry;

            while ($child) {
                $childid = (string)$child->id;

                if (!isset(self::$parents[$childid])) {
                    self::$parents[$childid] = null;

                    foreach ($child->link as $link) {
                        $href = (string)$link['href'];
                        if ($href != $entryid
                            && $importer->curie_equals($link['rel'], PluginImportLeap::NS_LEAP, 'is_part_of')
                            && $importer->entry_has_strategy($href, self::STRATEGY_IMPORT_AS_test, 'tests')) {
                            self::$parents[$childid] = $href;
                            break;
                        }
                    }
                }

                if (!self::$parents[$childid]) {
                    break;
                }
                if ($child = $importer->get_entry_by_id(self::$parents[$childid])) {
                    self::$ancestors[$entryid] = self::$parents[$childid];
                }
            }
        }

        return self::$ancestors[$entryid];
    }


    /**
     * Add import entry request for a test or a testask from the given entry
     * TODO: Refactor this to combine it with create_test()
     *
     * @param SimpleXMLElement $entry    The entry for the test or testask
     * @param PluginImportLeap $importer The importer
     */
    private static function add_import_entry_request_test(SimpleXMLElement $entry, PluginImportLeap $importer) {

        // First decide if it's going to be a test or a testask depending
        // on whether it has any ancestral tests.

        if ($ancestorid = self::get_ancestor_entryid($entry, $importer)) {
            $type = 'testask';
        }
        else {
            $type = 'test';
        }

        if (isset($entry->author->name) && strlen($entry->author->name)) {
            $authorname = $entry->author->name;
        }
        else {
            $author = $importer->get('usr');
        }

        // Set completiondate and completed status if we can find them
        if ($type === 'testask') {

            $namespaces = $importer->get_namespaces();
            $ns = $importer->get_leap2a_namespace();

            $dates = PluginImportLeap::get_leap_dates($entry, $namespaces, $ns);
            if (!empty($dates['target']['value'])) {
                $completiondate = strtotime($dates['target']['value']);
            }
            $completiondate = empty($completiondate) ? $updated : $completiondate;

            $completed = 0;
            if ($entry->xpath($namespaces[$ns] . ':status[@' . $namespaces[$ns] . ':stage="completed"]')) {
                $completed = 1;
            }
        }

        PluginImportLeap::add_import_entry_request($importer->get('importertransport')->get('importid'), (string)$entry->id, self::STRATEGY_IMPORT_AS_test, 'tests', array(
            'owner'   => $importer->get('usr'),
            'type'    => $type,
            'parent'  => $ancestorid,
            'content' => array(
                'title'       => (string)$entry->title,
                'description' => PluginImportLeap::get_entry_content($entry, $importer),
                'authorname'  => isset($authorname) ? $authorname : null,
                'author'      => isset($author) ? $author : null,
                'ctime'       => (string)$entry->published,
                'mtime'       => (string)$entry->updated,
                'completiondate' => ($type === 'testask') ? $completiondate : null,
                'completed'   => ($type === 'testask') ? $completed : null,
                'tags'        => PluginImportLeap::get_entry_tags($entry),
            ),
        ));
    }

    /**
     * Creates a test or testask from the given entry
     * TODO: Refactor this to combine it with add_import_entry_request_test()
     *
     * @param SimpleXMLElement $entry    The entry to create the test or testask from
     * @param PluginImportLeap $importer The importer
     * @return array A list of artefact IDs created, to be used with the artefact mapping.
     */
    private static function create_test(SimpleXMLElement $entry, PluginImportLeap $importer) {

        // First decide if it's going to be a test or a testask depending
        // on whether it has any ancestral tests.

        if (self::get_ancestor_entryid($entry, $importer)) {
            $artefact = new ArtefactTypeTestestask();
        }
        else {
            $artefact = new ArtefactTypeTest();
        }

        $artefact->set('title', (string)$entry->title);
        $artefact->set('description', PluginImportLeap::get_entry_content($entry, $importer));
        $artefact->set('owner', $importer->get('usr'));
        if (isset($entry->author->name) && strlen($entry->author->name)) {
            $artefact->set('authorname', $entry->author->name);
        }
        else {
            $artefact->set('author', $importer->get('usr'));
        }
        if ($published = strtotime((string)$entry->published)) {
            $artefact->set('ctime', $published);
        }
        if ($updated = strtotime((string)$entry->updated)) {
            $artefact->set('mtime', $updated);
        }

        $artefact->set('tags', PluginImportLeap::get_entry_tags($entry));

        // Set completiondate and completed status if we can find them
        if ($artefact instanceof ArtefactTypeTestestask) {

            $namespaces = $importer->get_namespaces();
            $ns = $importer->get_leap2a_namespace();

            $dates = PluginImportLeap::get_leap_dates($entry, $namespaces, $ns);
            if (!empty($dates['target']['value'])) {
                $completiondate = strtotime($dates['target']['value']);
            }
            $artefact->set('completiondate', empty($completiondate) ? $artefact->get('mtime') : $completiondate);

            if ($entry->xpath($namespaces[$ns] . ':status[@' . $namespaces[$ns] . ':stage="completed"]')) {
                $artefact->set('completed', 1);
            }
        }

        $artefact->commit();

        return array($artefact->get('id'));
    }

    /**
     * Set testask parents
     */
    public static function setup_relationships(SimpleXMLElement $entry, PluginImportLeap $importer) {
        if ($ancestorid = self::get_ancestor_entryid($entry, $importer)) {
            $ancestorids = $importer->get_artefactids_imported_by_entryid($ancestorid);
            $artefactids = $importer->get_artefactids_imported_by_entryid((string)$entry->id);
            if (empty($artefactids[0])) {
                throw new ImportException($importer, 'testask artefact not found: ' . (string)$entry->id);
            }
            if (empty($ancestorids[0])) {
                throw new ImportException($importer, 'test artefact not found: ' . $ancestorid);
            }
            $artefact = new ArtefactTypeTestestask($artefactids[0]);
            $artefact->set('parent', $ancestorids[0]);
            $artefact->commit();
        }
    }

    /**
     * Render import entry requests for Mahara tests and their testasks
     * @param PluginImportLeap $importer
     * @return HTML code for displaying tests and choosing how to import them
     */
    public static function render_import_entry_requests(PluginImportLeap $importer) {
        $importid = $importer->get('importertransport')->get('importid');
        // Get import entry requests for Mahara tests
        $entrytests = array();
        if ($iertests = get_records_select_array('import_entry_requests', 'importid = ? AND entrytype = ?', array($importid, 'test'))) {
            foreach ($iertests as $iertest) {
                $test = unserialize($iertest->entrycontent);
                $test['id'] = $iertest->id;
                $test['decision'] = $iertest->decision;
                if (is_string($iertest->duplicateditemids)) {
                    $iertest->duplicateditemids = unserialize($iertest->duplicateditemids);
                }
                if (is_string($iertest->existingitemids)) {
                    $iertest->existingitemids = unserialize($iertest->existingitemids);
                }
                $test['disabled'][PluginImport::DECISION_IGNORE] = false;
                $test['disabled'][PluginImport::DECISION_ADDNEW] = false;
                $test['disabled'][PluginImport::DECISION_APPEND] = true;
                $test['disabled'][PluginImport::DECISION_REPLACE] = true;
                if (!empty($iertest->duplicateditemids)) {
                    $duplicated_item = artefact_instance_from_id($iertest->duplicateditemids[0]);
                    $test['duplicateditem']['id'] = $duplicated_item->get('id');
                    $test['duplicateditem']['title'] = $duplicated_item->get('title');
                    $res = $duplicated_item->render_self(array());
                    $test['duplicateditem']['html'] = $res['html'];
                }
                else if (!empty($iertest->existingitemids)) {
                    foreach ($iertest->existingitemids as $id) {
                        $existing_item = artefact_instance_from_id($id);
                        $res = $existing_item->render_self(array());
                        $test['existingitems'][] = array(
                            'id'    => $existing_item->get('id'),
                            'title' => $existing_item->get('title'),
                            'html'  => $res['html'],
                        );
                    }
                }
                // Get import entry requests of testasks in the test
                $entrytestasks = array();
                if ($iertestasks = get_records_select_array('import_entry_requests', 'importid = ? AND entrytype = ? AND entryparent = ?',
                        array($importid, 'testask', $iertest->entryid))) {
                    foreach ($iertestasks as $iertestask) {
                        $testask = unserialize($iertestask->entrycontent);
                        $testask['id'] = $iertestask->id;
                        $testask['decision'] = $iertestask->decision;
                        $testask['completiondate'] = format_date($testask['completiondate'], 'strftimedate');
                        $testask['disabled'][PluginImport::DECISION_IGNORE] = false;
                        $testask['disabled'][PluginImport::DECISION_ADDNEW] = false;
                        $testask['disabled'][PluginImport::DECISION_APPEND] = true;
                        $testask['disabled'][PluginImport::DECISION_REPLACE] = true;
                        $entrytestasks[] = $testask;
                    }
                }
                $test['entrytestasks'] = $entrytestasks;
                $entrytests[] = $test;
            }
        }
        $smarty = smarty_core();
        $smarty->assign_by_ref('displaydecisions', $importer->get('displaydecisions'));
        $smarty->assign_by_ref('entrytests', $entrytests);
        return $smarty->fetch('artefact:tests:import/tests.tpl');
    }
}
