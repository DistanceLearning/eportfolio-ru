{if $tests}
<ul>
{foreach from=$tests item=test}
    <li><a href="{$test.link}">{$test.title}</a></li>
{/foreach}
</ul>
{/if}
