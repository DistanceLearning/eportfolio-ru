{include file="header.tpl"}
<div id="testswrap">
    <div class="rbuttons">
        <a class="btn" href="{$WWWROOT}artefact/tests/new.php">{str section="artefact.tests" tag="newtest"}</a>
    </div>
{if !$tests.data}
    <div class="message">{$strnotestsaddone|safe}</div>
{else}
<div id="testslist" class="fullwidth listing">
        {$tests.tablerows|safe}
</div>
   {$tests.pagination|safe}
{/if}
</div>
{include file="footer.tpl"}
