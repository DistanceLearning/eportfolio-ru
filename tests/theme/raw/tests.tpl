{include file="header.tpl"}
<div id="testswrap">
    <div class="rbuttons">
        <a class="btn" href="{$WWWROOT}artefact/tests/new/testask.php">{str section="artefact.tests" tag="newtestask"}</a>
    </div>
{if !$testasks.data}
    <div class="message">{$strnotestasksaddone|safe}</div>
{else}
<table id="testslist">
    <thead>
        <tr>
            <th class="completiondate">{str tag='completiondate' section='artefact.tests'}</th>
            <th class="testtitle">{str tag='title' section='artefact.tests'}</th>
            <th class="testdescription">{str tag='description' section='artefact.tests'}</th>
            <th class="testscontrols"></th>
            <th class="testscontrols"></th>
            <th class="testscontrols"></th>
        </tr>
    </thead>
    <tbody>
        {$testasks.tablerows|safe}
    </tbody>
</table>
   {$testasks.pagination|safe}
{/if}
</div>
{include file="footer.tpl"}
