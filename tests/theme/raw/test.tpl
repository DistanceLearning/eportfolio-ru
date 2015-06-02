{include file="header.tpl"}
<div id="testswrap">
    <div class="rbuttons">
        <a class="btn" href="{$WWWROOT}artefact/tests/new.php?id={$test}">{str section="artefact.tests" tag="newtestask"}</a>
    </div>
    {if $tags}<p class="tags s"><label>{str tag=tags}:</label> {list_tags owner=$owner tags=$tags}</p>{/if}
{if !$testasks.data}
    <div>{$teststestasksdescription}</div>
    <div class="message">{$strnotestasksaddone|safe}</div>
{else}
<table id="testaskslist" class="fullwidth listing">
    <thead>
        <tr>
            <th>{str tag='completiondate' section='artefact.tests'}</th>
            <th>{str tag='title' section='artefact.tests'}</th>
            <th>{str tag='description' section='artefact.tests'}</th>
            <th class="center">{str tag='completed' section='artefact.tests'}</th>
            <th></th>
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
