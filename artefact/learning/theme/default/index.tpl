{include file="header.tpl"}

<div id="column-right">
{include file="sidebar.tpl"}
</div>
{include file="columnleftstart.tpl"}
{$multipleintelligences}
{contextualhelp plugintype='artefact' pluginname='learning' section='addmultipleintelligences'}
<p>{str tag="multipleintelligencesdesc" section="artefact.learning"}</p>
<br>
{$learningstyles}
{contextualhelp plugintype='artefact' pluginname='learning' section='addlearningstyles'}
<p>{str tag="learningstylesdesc" section="artefact.learning"}</p>
{include file="columnleftend.tpl"}
{include file="footer.tpl"}
