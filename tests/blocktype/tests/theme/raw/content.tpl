{if $tags}<p class="tags s"><label>{str tag=tags}:</label> {list_tags owner=$owner tags=$tags}</p>{/if}
{if $testasks.data}
<table id="testasktable_{$blockid}" class="testsblocktable fullwidth">
    <thead>
        <tr>
            <th class="c1">{str tag='completiondate' section='artefact.tests'}</th>
            <th class="c2">{str tag='title' section='artefact.tests'}</th>
            <th class="c3 center">{str tag='completed' section='artefact.tests'}</th>
        </tr>
    </thead>
    <tbody>
    {$testasks.tablerows|safe}
    </tbody>
</table>
{if $testasks.pagination}
<div id="tests_page_container_{$blockid}" class="nojs-hidden-block">{$testasks.pagination|safe}</div>
{/if}
{else}
    <p>{str tag='notestasks' section='artefact.tests'}</p>
{/if}
