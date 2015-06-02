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

class PluginArtefacttests extends PluginArtefact {

    public static function get_artefact_types() {
        return array(
            'testask',
            'test',
        );
    }

    public static function get_block_types() {
        return array();
    }

    public static function get_plugin_name() {
        return 'tests';
    }

    public static function is_active() {
        return get_field('artefact_installed', 'active', 'name', 'tests');
    }

    public static function menu_items() {
        return array(
            'content/tests' => array(
                'path' => 'content/tests',
                'url'  => 'artefact/tests/index.php',
                'title' => get_string('tests', 'artefact.tests'),
                'weight' => 60,
            ),
        );
    }

    public static function get_artefact_type_content_types() {
        return array(
            'testask' => array('text'),
        );
    }

    public static function progressbar_link($artefacttype) {
        return 'artefact/tests/index.php';
    }
}

class ArtefactTypeTest extends ArtefactType {

    public function __construct($id = 0, $data = null) {
        parent::__construct($id, $data);
        if (empty($this->id)) {
            $this->container = 1;
        }
    }

    public static function get_links($id) {
        return array(
            '_default' => get_config('wwwroot') . 'artefact/tests/test.php?id=' . $id,
        );
    }

    public function delete() {
        if (empty($this->id)) {
            return;
        }

        db_begin();
        parent::delete();
        db_commit();
    }

    public static function get_icon($options=null) {
        global $THEME;
        return $THEME->get_url('images/test.png', false, 'artefact/tests');
    }

    public static function is_singular() {
        return false;
    }


    /**
     * This function returns a list of the given user's tests.
     *
     * @param limit how many tests to display per page
     * @param offset current page to display
     * @return array (count: integer, data: array)
     */
    public static function get_tests($offset=0, $limit=10) {
        global $USER;

        ($tests = get_records_sql_array("SELECT * FROM {artefact}
                                        WHERE owner = ? AND artefacttype = 'test'
                                        ORDER BY title ASC", array($USER->get('id')), $offset, $limit))
                                        || ($tests = array());
        foreach ($tests as &$test) {
            if (!isset($test->tags)) {
                $test->tags = ArtefactType::artefact_get_tags($test->id);
            }
            $test->description = '<p>' . preg_replace('/\n\n/','</p><p>', $test->description) . '</p>';
        }
        $result = array(
            'count'  => count_records('artefact', 'owner', $USER->get('id'), 'artefacttype', 'test'),
            'data'   => $tests,
            'offset' => $offset,
            'limit'  => $limit,
        );

        return $result;
    }

    /**
     * Builds the tests list table
     *
     * @param tests (reference)
     */
    public static function build_tests_list_html(&$tests) {
        $smarty = smarty_core();
        $smarty->assign_by_ref('tests', $tests);
        $tests['tablerows'] = $smarty->fetch('artefact:tests:testslist.tpl');
        $pagination = build_pagination(array(
            'id' => 'testlist_pagination',
            'class' => 'center',
            'url' => get_config('wwwroot') . 'artefact/tests/index.php',
            'jsonscript' => 'artefact/tests/tests.json.php',
            'datatable' => 'testslist',
            'count' => $tests['count'],
            'limit' => $tests['limit'],
            'offset' => $tests['offset'],
            'firsttext' => '',
            'previoustext' => '',
            'nexttext' => '',
            'lasttext' => '',
            'numbersincludefirstlast' => false,
            'resultcounttextsingular' => get_string('test', 'artefact.tests'),
            'resultcounttextplural' => get_string('tests', 'artefact.tests'),
        ));
        $tests['pagination'] = $pagination['html'];
        $tests['pagination_js'] = $pagination['javascript'];
    }

    public static function validate(Pieform $form, $values) {
        global $USER;
        if (!empty($values['test'])) {
            $id = (int) $values['test'];
            $artefact = new ArtefactTypeTest($id);
            if (!$USER->can_edit_artefact($artefact)) {
                $form->set_error('submit', get_string('canteditdontowntest', 'artefact.tests'));
            }
        }
    }

    public static function submit(Pieform $form, $values) {
        global $USER, $SESSION;

        $new = false;

        if (!empty($values['test'])) {
            $id = (int) $values['test'];
            $artefact = new ArtefactTypeTest($id);
        }
        else {
            $artefact = new ArtefactTypeTest();
            $artefact->set('owner', $USER->get('id'));
            $new = true;
        }

        $artefact->set('title', $values['title']);
        $artefact->set('description', $values['description']);
        if (get_config('licensemetadata')) {
            $artefact->set('license', $values['license']);
            $artefact->set('licensor', $values['licensor']);
            $artefact->set('licensorurl', $values['licensorurl']);
        }
        $artefact->set('tags', $values['tags']);
        $artefact->commit();

        $SESSION->add_ok_msg(get_string('testsavedsuccessfully', 'artefact.tests'));

        if ($new) {
            redirect('/artefact/tests/test.php?id='.$artefact->get('id'));
        }
        else {
            redirect('/artefact/tests/index.php');
        }
    }

    /**
    * Gets the new/edit tests pieform
    *
    */
    public static function get_form($test=null) {
        require_once(get_config('libroot') . 'pieforms/pieform.php');
        require_once('license.php');
        $elements = call_static_method(generate_artefact_class_name('test'), 'get_testform_elements', $test);
        $elements['submit'] = array(
            'type' => 'submitcancel',
            'value' => array(get_string('savetest','artefact.tests'), get_string('cancel')),
            'goto' => get_config('wwwroot') . 'artefact/tests/index.php',
        );
        $testform = array(
            'name' => empty($test) ? 'addtest' : 'edittest',
            'plugintype' => 'artefact',
            'pluginname' => 'testask',
            'validatecallback' => array(generate_artefact_class_name('test'),'validate'),
            'successcallback' => array(generate_artefact_class_name('test'),'submit'),
            'elements' => $elements,
        );

        return pieform($testform);
    }

    /**
    * Gets the new/edit fields for the test pieform
    *
    */
    public static function get_testform_elements($test) {
        $elements = array(
            'title' => array(
                'type' => 'text',
                'defaultvalue' => null,
                'title' => get_string('title', 'artefact.tests'),
                'size' => 30,
                'rules' => array(
                    'required' => true,
                ),
            ),
            'description' => array(
                'type'  => 'textarea',
                'rows' => 10,
                'cols' => 50,
                'resizable' => false,
                'defaultvalue' => null,
                'title' => get_string('description', 'artefact.tests'),
            ),
            'tags'        => array(
                'type'        => 'tags',
                'title'       => get_string('tags'),
                'description' => get_string('tagsdescprofile'),
            ),
        );

        if (!empty($test)) {
            foreach ($elements as $k => $element) {
                $elements[$k]['defaultvalue'] = $test->get($k);
            }
            $elements['test'] = array(
                'type' => 'hidden',
                'value' => $test->id,
            );
        }

        if (get_config('licensemetadata')) {
            $elements['license'] = license_form_el_basic($test);
            $elements['license_advanced'] = license_form_el_advanced($test);
        }

        return $elements;
    }

    public function render_self($options) {
        $this->add_to_render_path($options);

        $limit = !isset($options['limit']) ? 10 : (int) $options['limit'];
        $offset = isset($options['offset']) ? intval($options['offset']) : 0;

        $testasks = ArtefactTypeTestestask::get_testasks($this->id, $offset, $limit);

        $template = 'artefact:tests:testaskrows.tpl';

        $baseurl = get_config('wwwroot') . 'view/artefact.php?artefact=' . $this->id;
        if (!empty($options['viewid'])) {
            $baseurl .= '&view=' . $options['viewid'];
        }

        $pagination = array(
            'baseurl' => $baseurl,
            'id' => 'testask_pagination',
            'datatable' => 'testasktable',
            'jsonscript' => 'artefact/tests/viewtestasks.json.php',
        );

        ArtefactTypeTestestask::render_testasks($testasks, $template, $options, $pagination);

        $smarty = smarty_core();
        $smarty->assign_by_ref('testasks', $testasks);
        if (isset($options['viewid'])) {
            $smarty->assign('artefacttitle', '<a href="' . $baseurl . '">' . hsc($this->get('title')) . '</a>');
        }
        else {
            $smarty->assign('artefacttitle', hsc($this->get('title')));
        }
        $smarty->assign('test', $this);

        if (!empty($options['details']) and get_config('licensemetadata')) {
            $smarty->assign('license', render_license($this));
        }
        else {
            $smarty->assign('license', false);
        }
        $smarty->assign('owner', $this->get('owner'));
        $smarty->assign('tags', $this->get('tags'));

        return array('html' => $smarty->fetch('artefact:tests:viewtest.tpl'), 'javascript' => '');
    }

    public static function is_countable_progressbar() {
        return true;
    }
}

class ArtefactTypeTestestask extends ArtefactType {

    protected $completed = 0;
    protected $completiondate;

    /**
     * We override the constructor to fetch the extra data.
     *
     * @param integer
     * @param object
     */
    public function __construct($id = 0, $data = null) {
        parent::__construct($id, $data);

        if ($this->id) {
            if ($pdata = get_record('artefact_tests_testask', 'artefact', $this->id, null, null, null, null, '*, ' . db_format_tsfield('completiondate'))) {
                foreach($pdata as $name => $value) {
                    if (property_exists($this, $name)) {
                        $this->$name = $value;
                    }
                }
            }
            else {
                // This should never happen unless the user is playing around with testask IDs in the location bar or similar
                throw new ArtefactNotFoundException(get_string('testaskdoesnotexist', 'artefact.tests'));
            }
        }
    }

    public static function get_links($id) {
        return array(
            '_default' => get_config('wwwroot') . 'artefact/tests/edit/testask.php?id=' . $id,
        );
    }

    public static function get_icon($options=null) {
        global $THEME;
        return $THEME->get_url('images/testtestask.png', false, 'artefact/tests');
    }

    public static function is_singular() {
        return false;
    }

    /**
     * This method extends ArtefactType::commit() by adding additional data
     * into the artefact_tests_testask table.
     *
     */
    public function commit() {
        if (empty($this->dirty)) {
            return;
        }

        // Return whether or not the commit worked
        $success = false;

        db_begin();
        $new = empty($this->id);

        parent::commit();

        $this->dirty = true;

        $completiondate = $this->get('completiondate');
        if (!empty($completiondate)) {
            $date = db_format_timestamp($completiondate);
        }
        $data = (object)array(
            'artefact'  => $this->get('id'),
            'completed' => $this->get('completed'),
            'completiondate' => $date,
        );

        if ($new) {
            $success = insert_record('artefact_tests_testask', $data);
        }
        else {
            $success = update_record('artefact_tests_testask', $data, 'artefact');
        }

        db_commit();

        $this->dirty = $success ? false : true;

        return $success;
    }

    /**
     * This function extends ArtefactType::delete() by also deleting anything
     * that's in testask.
     */
    public function delete() {
        if (empty($this->id)) {
            return;
        }

        db_begin();
        delete_records('artefact_tests_testask', 'artefact', $this->id);

        parent::delete();
        db_commit();
    }

    public static function bulk_delete($artefactids) {
        if (empty($artefactids)) {
            return;
        }

        $idstr = join(',', array_map('intval', $artefactids));

        db_begin();
        delete_records_select('artefact_tests_testask', 'artefact IN (' . $idstr . ')');
        parent::bulk_delete($artefactids);
        db_commit();
    }


    /**
    * Gets the new/edit testasks pieform
    *
    */
    public static function get_form($parent, $testask=null) {
        require_once(get_config('libroot') . 'pieforms/pieform.php');
        require_once('license.php');
        $elements = call_static_method(generate_artefact_class_name('testask'), 'get_testaskform_elements', $parent, $testask);
        $elements['submit'] = array(
            'type' => 'submitcancel',
            'value' => array(get_string('savetestask','artefact.tests'), get_string('cancel')),
            'goto' => get_config('wwwroot') . 'artefact/tests/test.php?id=' . $parent,
        );
        $testaskform = array(
            'name' => empty($testask) ? 'addtestasks' : 'edittestask',
            'plugintype' => 'artefact',
            'pluginname' => 'testask',
            'validatecallback' => array(generate_artefact_class_name('testask'),'validate'),
            'successcallback' => array(generate_artefact_class_name('testask'),'submit'),
            'elements' => $elements,
        );

        return pieform($testaskform);
    }

    /**
    * Gets the new/edit fields for the testasks pieform
    *
    */
    public static function get_testaskform_elements($parent, $testask=null) {
        $elements = array(
            'title' => array(
                'type' => 'text',
                'defaultvalue' => null,
                'title' => get_string('title', 'artefact.tests'),
                'description' => get_string('titledesc','artefact.tests'),
                'size' => 30,
                'rules' => array(
                    'required' => true,
                ),
            ),
            'completiondate' => array(
                'type'       => 'calendar',
                'caloptions' => array(
                    'showsTime'      => false,
                    'ifFormat'       => '%Y/%m/%d'
                    ),
                'defaultvalue' => null,
                'title' => get_string('completiondate', 'artefact.tests'),
                'description' => get_string('dateformatguide'),
                'rules' => array(
                    'required' => true,
                ),
            ),
            'description' => array(
                'type'  => 'textarea',
                'rows' => 10,
                'cols' => 50,
                'resizable' => false,
                'defaultvalue' => null,
                'title' => get_string('description', 'artefact.tests'),
            ),
            'tags'        => array(
                'type'        => 'tags',
                'title'       => get_string('tags'),
                'description' => get_string('tagsdescprofile'),
            ),
            'completed' => array(
                'type' => 'checkbox',
                'defaultvalue' => null,
                'title' => get_string('completed', 'artefact.tests'),
                'description' => get_string('completeddesc', 'artefact.tests'),
            ),
        );

        if (!empty($testask)) {
            foreach ($elements as $k => $element) {
                $elements[$k]['defaultvalue'] = $testask->get($k);
            }
            $elements['testask'] = array(
                'type' => 'hidden',
                'value' => $testask->id,
            );
        }
        if (get_config('licensemetadata')) {
            $elements['license'] = license_form_el_basic($testask);
            $elements['license_advanced'] = license_form_el_advanced($testask);
        }

        $elements['parent'] = array(
            'type' => 'hidden',
            'value' => $parent,
        );

        return $elements;
    }

    public static function validate(Pieform $form, $values) {
        global $USER;
        if (!empty($values['testask'])) {
            $id = (int) $values['testask'];
            $artefact = new ArtefactTypeTestestask($id);
            if (!$USER->can_edit_artefact($artefact)) {
                $form->set_error('submit', get_string('canteditdontowntestask', 'artefact.tests'));
            }
        }
    }

    public static function submit(Pieform $form, $values) {
        global $USER, $SESSION;

        if (!empty($values['testask'])) {
            $id = (int) $values['testask'];
            $artefact = new ArtefactTypeTestestask($id);
        }
        else {
            $artefact = new ArtefactTypeTestestask();
            $artefact->set('owner', $USER->get('id'));
            $artefact->set('parent', $values['parent']);
        }

        $artefact->set('title', $values['title']);
        $artefact->set('description', $values['description']);
        $artefact->set('completed', $values['completed'] ? 1 : 0);
        $artefact->set('completiondate', $values['completiondate']);
        if (get_config('licensemetadata')) {
            $artefact->set('license', $values['license']);
            $artefact->set('licensor', $values['licensor']);
            $artefact->set('licensorurl', $values['licensorurl']);
        }
        $artefact->set('tags', $values['tags']);
        $artefact->commit();

        $SESSION->add_ok_msg(get_string('testsavedsuccessfully', 'artefact.tests'));

        redirect('/artefact/tests/test.php?id='.$values['parent']);
    }

    /**
     * This function returns a list of the current tests testasks.
     *
     * @param limit how many testasks to display per page
     * @param offset current page to display
     * @return array (count: integer, data: array)
     */
    public static function get_testasks($test, $offset=0, $limit=10) {
        $datenow = time(); // time now to use for formatting testasks by completion

        ($results = get_records_sql_array("
            SELECT a.id, at.artefact AS testask, at.completed, ".db_format_tsfield('completiondate').",
                a.title, a.description, a.parent
                FROM {artefact} a
            JOIN {artefact_tests_testask} at ON at.artefact = a.id
            WHERE a.artefacttype = 'testask' AND a.parent = ?
            ORDER BY at.completiondate ASC, a.id", array($test), $offset, $limit))
            || ($results = array());

        // format the date and setup completed for display if testask is incomplete
        if (!empty($results)) {
            foreach ($results as $result) {
                if (!empty($result->completiondate)) {
                    // if record hasn't been completed and completiondate has passed mark as such for display
                    if ($result->completiondate < $datenow && !$result->completed) {
                        $result->completed = -1;
                    }
                    $result->completiondate = format_date($result->completiondate, 'strftimedate');
                }
                $result->description = '<p>' . preg_replace('/\n\n/','</p><p>', $result->description) . '</p>';
            }
        }

        $result = array(
            'count'  => count_records('artefact', 'artefacttype', 'testask', 'parent', $test),
            'data'   => $results,
            'offset' => $offset,
            'limit'  => $limit,
            'id'     => $test,
        );

        return $result;
    }

    /**
     * Builds the testasks list table for current test
     *
     * @param testasks (reference)
     */
    public function build_testasks_list_html(&$testasks) {
        $smarty = smarty_core();
        $smarty->assign_by_ref('testasks', $testasks);
        $testasks['tablerows'] = $smarty->fetch('artefact:tests:testaskslist.tpl');
        $pagination = build_pagination(array(
            'id' => 'testasklist_pagination',
            'class' => 'center',
            'url' => get_config('wwwroot') . 'artefact/tests/test.php?id='.$testasks['id'],
            'jsonscript' => 'artefact/tests/testasks.json.php',
            'datatable' => 'testaskslist',
            'count' => $testasks['count'],
            'limit' => $testasks['limit'],
            'offset' => $testasks['offset'],
            'firsttext' => '',
            'previoustext' => '',
            'nexttext' => '',
            'lasttext' => '',
            'numbersincludefirstlast' => false,
            'resultcounttextsingular' => get_string('testask', 'artefact.tests'),
            'resultcounttextplural' => get_string('testasks', 'artefact.tests'),
        ));
        $testasks['pagination'] = $pagination['html'];
        $testasks['pagination_js'] = $pagination['javascript'];
    }

    // @TODO: make blocktype use this too
    public function render_testasks(&$testasks, $template, $options, $pagination) {
        $smarty = smarty_core();
        $smarty->assign_by_ref('testasks', $testasks);
        $smarty->assign_by_ref('options', $options);
        $testasks['tablerows'] = $smarty->fetch($template);

        if ($testasks['limit'] && $pagination) {
            $pagination = build_pagination(array(
                'id' => $pagination['id'],
                'class' => 'center',
                'datatable' => $pagination['datatable'],
                'url' => $pagination['baseurl'],
                'jsonscript' => $pagination['jsonscript'],
                'count' => $testasks['count'],
                'limit' => $testasks['limit'],
                'offset' => $testasks['offset'],
                'numbersincludefirstlast' => false,
                'resultcounttextsingular' => get_string('testask', 'artefact.tests'),
                'resultcounttextplural' => get_string('testasks', 'artefact.tests'),
            ));
            $testasks['pagination'] = $pagination['html'];
            $testasks['pagination_js'] = $pagination['javascript'];
        }
    }

    public static function is_countable_progressbar() {
        return true;
    }
}
