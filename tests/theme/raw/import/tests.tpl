{if count($entrytests)}
<div class="section fullwidth">
    <h2>{str tag=test section=artefact.tests}</h2>
</div>
{foreach from=$entrytests item=test}
<div class="{cycle name=rows values='r0,r1'} listrow">
    <div id="entrytest" class="indent1">
        <div class="importcolumn importcolumn1">
            <h3 class="title">
            {if $test.description}<a class="testtitle" href="" id="{$test.id}">{/if}
            {$test.title|str_shorten_text:80:true}
            {if $test.description}</a>{/if}
            </h3>
            <div id="{$test.id}_desc" class="detail hidden">{$test.description|clean_html|safe}</div>
            {if $test.tags}
            <div class="tags">
                <label>{str tag=tags}:</label> {list_tags owner=0 tags=$test.tags}
            </div>
            {/if}
            <div class="testasks">
                <label>{str tag=testasks section=artefact.tests}:</label>
                {if count($test.entrytestasks)}<a class="showtestasks" href="" id="{$test.id}">{/if}
                {str tag=ntestasks section=artefact.tests arg1=count($test.entrytestasks)}
                {if count($test.entrytestasks)}</a>{/if}
            </div>
        </div>
        <div class="importcolumn importcolumn2">
            {if $test.duplicateditem}
            <div class="duplicatedtest">
                <label>{str tag=duplicatedtest section=artefact.tests}:</label> <a class="showduplicatedtest" href="" id="{$test.duplicateditem.id}">{$test.duplicateditem.title|str_shorten_text:80:true}</a>
                <div id="{$test.duplicateditem.id}_duplicatedtest" class="detail hidden">{$test.duplicateditem.html|clean_html|safe}</div>
            </div>
            {/if}
            {if $test.existingitems}
            <div class="existingtests">
                <label>{str tag=existingtests section=artefact.tests}:</label>
                   {foreach from=$test.existingitems item=existingitem}
                   <a class="showexistingtest" href="" id="{$existingitem.id}">{$existingitem.title|str_shorten_text:80:true}</a><br>
                   <div id="{$existingitem.id}_existingtest" class="detail hidden">{$existingitem.html|clean_html|safe}</div>
                   {/foreach}
            </div>
            {/if}
        </div>
        <div class="importcolumn importcolumn3">
            {foreach from=$displaydecisions key=opt item=displayopt}
                {if !$test.disabled[$opt]}
                <input id="decision_{$test.id}_{$opt}" class="testdecision" id="{$test.id}" type="radio" name="decision_{$test.id}" value="{$opt}"{if $test.decision == $opt} checked="checked"{/if}>
                <label for="decision_{$test.id}_{$opt}">{$displayopt}<span class="accessible-hidden">({$test.title})</span></label><br>
                {/if}
            {/foreach}
        </div>
        <div class="cb"></div>
    </div>
    <div id="{$test.id}_testasks" class="indent2 hidden">
    {foreach from=$test.entrytestasks item=testask}
        <div id="testasktitle_{$testask.id}" class="{cycle name=rows values='r0,r1'} listrow">
            <div class="importcolumn importcolumn1">
                <h4 class="title"><a class="testasktitle" href="" id="{$testask.id}">{$testask.title|str_shorten_text:80:true}</a></h4>
                <div id="{$testask.id}_desc" class="detail hidden">
                    {$testask.description|clean_html|safe}
                </div>
                <div class="completiondate"><label>{str tag='completiondate' section='artefact.tests'}:</label> {$testask.completiondate}</div>
                {if $testask.completed == 1}<div class="completed">{str tag=completed section=artefact.tests}</div>{/if}
            </div>
            <div class="importcolumn importcolumn2">
            &nbsp;
            </div>
            <div class="importcolumn importcolumn3">
                {foreach from=$displaydecisions key=opt item=displayopt}
                    {if !$testask.disabled[$opt]}
                    <input id="decision_{$testask.id}_{$opt}" class="testaskdecision" type="radio" name="decision_{$testask.id}" value="{$opt}"{if $testask.decision == $opt} checked="checked"{/if}>
                    <label for="decision_{$testask.id}_{$opt}">{$displayopt}<span class="accessible-hidden">({$testask.title})</span></label><br>
                    {/if}
                {/foreach}
            </div>
            <div class="cb"></div>
        </div>
    {/foreach}
    </div>
    <div class="cb"></div>
</div>
{/foreach}
<script type="text/javascript">
    jQuery(function() {
        jQuery("a.testtitle").click(function(e) {
            e.preventDefault();
            jQuery("#" + this.id + "_desc").toggleClass("hidden");
        });
        jQuery("a.testasktitle").click(function(e) {
            e.preventDefault();
            jQuery("#" + this.id + "_desc").toggleClass("hidden");
        });
        jQuery("a.showduplicatedtest").click(function(e) {
            e.preventDefault();
            jQuery("#" + this.id + "_duplicatedtest").toggleClass("hidden");
        });
        jQuery("a.showexistingtest").click(function(e) {
            e.preventDefault();
            jQuery("#" + this.id + "_existingtest").toggleClass("hidden");
        });
       jQuery("a.showtestasks").click(function(e) {
            e.preventDefault();
            jQuery("#" + this.id + "_testasks").toggleClass("hidden");
        });
        jQuery("input.testdecision").change(function(e) {
            e.preventDefault();
            if (this.value == '1') {
            // The import decision for the test is IGNORE
            // Set decision for its testasks to be IGNORE as well
                jQuery("#" + this.id + "_testasks input.testaskdecision[value=1]").prop('checked', true);
            }
        });
    });
</script>
{/if}
