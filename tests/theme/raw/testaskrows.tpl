        {foreach from=$testasks.data item=testask}
        {if $testask->completed == -1}
            <tr class="test_incomplete">
                <td class="c1 completiondate">{$testask->completiondate}</td>
{if $testask->description}
                <td class="testtestasktitledescript"><a class="testask-title" href="">{$testask->title}</a>
                <div class="testask-desc hidden">{$testask->description|clean_html|safe}</div></td>
{else}
                <td class="testtestasktitle">{$testask->title}</td>
{/if}
                <td class="c3 incomplete"><img src="{$WWWROOT}theme/raw/static/images/failure_small.png" alt="{str tag=overdue section=artefact.tests}" /></td>
            </tr>
        {else}
            <tr class="{cycle values='r0,r1'}">
                <td class="c1 completiondate">{$testask->completiondate}</td>
{if $testask->description}
                <td class="testtestasktitledescript"><a class="testask-title" href="">{$testask->title}</a>
                <div class="testask-desc hidden" id="testask-desc-{$testask->id}">{$testask->description|clean_html|safe}</div></td>
{else}
                <td class="testtestasktitle">{$testask->title}</td>
{/if}
                {if $testask->completed == 1}
                    <td class="c3 completed"><img src="{$WWWROOT}theme/raw/static/images/success_small.png" alt="{str tag=completed section=artefact.tests}" /></td>
                {else}
                    <td><span class="accessible-hidden">{str tag=incomplete section=artefact.tests}</span></td>
                {/if}
            </tr>
        {/if}
