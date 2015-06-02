{if $tags}<p class="tags s"><label>{str tag=tags}:</label> {list_tags owner=$owner tags=$tags}</p>{/if}
<table id="testasktable">
    <thead>
        <tr>
            <th class="c1">{str tag='completiondate' section='artefact.tests'}</th>
            <th class="c2">{str tag='title' section='artefact.tests'}</th>
            <th class="c3">{str tag='completed' section='artefact.tests'}</th>
        </tr>
    </thead>
    <tbody>
    {$testasks.tablerows|safe}
    </tbody>
</table>
<div id="tests_page_container">{$testasks.pagination|safe}</div>
{if $license}
<div class="resumelicense">
{$license|safe}
</div>
{/if}
<script>
{literal}
function rewritetestaskTitles() {
    forEach(
        getElementsByTagAndClassName('a', 'testask-title','testasktable'),
        function(element) {
            connect(element, 'onclick', function(e) {
                e.stop();
                var description = getFirstElementByTagAndClassName('div', 'testask-desc', element.parentNode);
                toggleElementClass('hidden', description);
            });
        }
    );
}

addLoadEvent(function() {
    {/literal}{$testasks.pagination_js|safe}{literal}
    removeElementClass('tests_page_container', 'hidden');
});

function testaskPager() {
    var self = this;
    paginatorProxy.addObserver(self);
    connect(self, 'pagechanged', rewritetestaskTitles);
}
var testaskPager = new testaskPager();
addLoadEvent(rewritetestaskTitles);
{/literal}
</script>
