<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--[if lt IE 7 ]> <html{if $LANGDIRECTION == 'rtl'} dir="rtl"{/if} class="ie ie6"> <![endif]-->
<!--[if IE 7 ]>    <html{if $LANGDIRECTION == 'rtl'} dir="rtl"{/if} class="ie ie7"> <![endif]-->
<!--[if IE 8 ]>    <html{if $LANGDIRECTION == 'rtl'} dir="rtl"{/if} class="ie ie8"> <![endif]-->
<!--[if IE 9 ]>    <html{if $LANGDIRECTION == 'rtl'} dir="rtl"{/if} class="ie ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->  <html{if $LANGDIRECTION == 'rtl'} dir="rtl"{/if}><!--<![endif]-->
{include file="header/head.tpl"}
<body id="micro" class="no-js">
{if $USERMASQUERADING  || $SITECLOSED || $SITETOP}<div class="sitemessages">{/if}
    {if $USERMASQUERADING}<div class="sitemessage"><img src="{theme_url filename='images/failure.png'}" alt="">{$masqueradedetails} {$becomeyouagain|safe}</div>{/if}
   
    {if $SITECLOSED}<div class="sitemessage center">{if $SITECLOSED == 'logindisabled'}{str tag=siteclosedlogindisabled section=mahara arg1="`$WWWROOT`admin/upgrade.php"}{else}{str tag=siteclosed}{/if}</div>{/if}
    {if $SITETOP}<div id="switchwrap">{$SITETOP|safe}</div>{/if}
{if $USERMASQUERADING || $SITECLOSED || $SITETOP}</div>{/if}
<div id="container">
    <div id="loading-box"></div>
    <div id="top-wrapper"><div id="header">
        <h1 class="hidden"><a href="{$WWWROOT}">{$hiddenheading|default:"Mahara"|escape}</a></h1>
    </div></div>
    <div id="mainmiddlewrap">
        <div id="mainmiddle">
            <div id="main-wrapper">
                <div id="main-column" class="main-column">
                    <div id="main-column-container">
                        {dynamic}{insert_messages}{/dynamic}
{if isset($PAGEHEADING)}                    <h1>{$PAGEHEADING}{if $PAGEHELPNAME}<span class="page-help-icon">{$PAGEHELPICON|safe}</span>{/if}</h1>
{/if}
