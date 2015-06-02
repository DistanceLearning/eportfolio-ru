{foreach from=$tests.data item=test}
    <div class="{cycle values='r0,r1'} listrow">
            <h3 class="title"><a href="{$WWWROOT}artefact/tests/test.php?id={$test->id}">{$test->title}</a></h3>

            <div class="fr teststatus">
                <a href="{$WWWROOT}artefact/tests/edit/index.php?id={$test->id}" title="{str tag=edit}" >
                    <img src="{theme_url filename='images/btn_edit.png'}" alt="{str(tag=editspecific arg1=$test->title)|escape:html|safe}"></a>
                <a href="{$WWWROOT}artefact/tests/test.php?id={$test->id}" title="{str tag=managetestasks section=artefact.tests}">
                    <img src="{theme_url filename='images/btn_configure.png'}" alt="{str(tag=managetestasksspecific section=artefact.tests arg1=$test->title)|escape:html|safe}"></a>
                <a href="{$WWWROOT}artefact/tests/delete/index.php?id={$test->id}" title="{str tag=delete}">
                    <img src="{theme_url filename='images/btn_deleteremove.png'}" alt="{str(tag=deletespecific arg1=$test->title)|escape:html|safe}"></a>
            </div>

            <div class="detail">{$test->description|clean_html|safe}</div>
            {if $test->tags}
            <div>{str tag=tags}: {list_tags tags=$test->tags owner=$test->owner}</div>
            {/if}
            <div class="cb"></div>
    </div>
{/foreach}
