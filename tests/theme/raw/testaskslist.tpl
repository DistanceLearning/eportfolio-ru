{foreach from=$testasks.data item=testask}
    {if $testask->completed == -1}
        <tr class="incomplete">
            <td class="completiondate">{$testask->completiondate}</td>
            <td class="testtestasktitle">{$testask->title}</td>
            <td class="testtestaskdescription">{$testask->description|clean_html|safe}</td>
            <td class="incomplete"><img src="{$WWWROOT}theme/raw/static/images/failure_small.png" alt="{str tag=overdue section=artefact.tests}" /></td>
    {else}
        <tr class="{cycle values='r0,r1'}">
            <td class="completiondate">{$testask->completiondate}</td>
            <td class="testtestasktitle">{$testask->title}</td>
            <td class="testtestaskdescription">{$testask->description|clean_html|safe}</td>
            {if $testask->completed == 1}
                <td class="completed"><img src="{$WWWROOT}theme/raw/static/images/success_small.png" alt="{str tag=completed section=artefact.tests}" /></td>
            {else}
                <td><span class="accessible-hidden">{str tag=incomplete section=artefact.tests}</span></td>
            {/if}

    {/if}
            <td class="buttonscell btns2 testscontrols">
                <a href="{$WWWROOT}artefact/tests/edit/testask.php?id={$testask->testask}" title="{str tag=edit}">
                    <img src="{theme_url filename='images/btn_edit.png'}" alt="{str(tag=editspecific arg1=$testask->title)|escape:html|safe}">
                </a>
                <a href="{$WWWROOT}artefact/tests/delete/testask.php?id={$testask->testask}" title="{str tag=delete}">
                    <img src="{theme_url filename='images/btn_deleteremove.png'}" alt="{str(tag=deletespecific arg1=$testask->title)|escape:html|safe}">
                </a>
            </td>
        </tr>
{/foreach}
