function rewritetestaskTitles(blockid) {
    forEach(
        getElementsByTagAndClassName('a', 'testask-title', 'testasktable_' + blockid),
        function(element) {
            disconnectAll(element);
            connect(element, 'onclick', function(e) {
                e.stop();
                var description = getFirstElementByTagAndClassName('div', 'testask-desc', element.parentNode);
                toggleElementClass('hidden', description);
            });
        }
    );
}
function testaskPager(blockid) {
    var self = this;
    paginatorProxy.addObserver(self);
    connect(self, 'pagechanged', partial(rewritetestaskTitles, blockid));
}

var testaskPagers = [];

function initNewtestsBlock(blockid) {
    if ($('tests_page_container_' + blockid)) {
        new Paginator('block' + blockid + '_pagination', 'testasktable_' + blockid, null, 'artefact/tests/viewtestasks.json.php', null);
        testaskPagers.push(new testaskPager(blockid));
    }
    rewritetestaskTitles(blockid);
}
