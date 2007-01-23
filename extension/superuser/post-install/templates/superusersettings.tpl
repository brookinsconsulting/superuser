<form method="post" action={'package/install'|ezurl}>

    {include uri="design:package/install/error.tpl"}
    {include uri="design:package/install_header.tpl"}

    <p>The super user login handler will be prepended to the login handler list of each site access you select below.</p>

    <div class="element">
        {'Select site accesses'|i18n('design/admin/settings')}:<br />
        {foreach $siteaccess_list as $number => $siteaccess}
        <div class="inline"><input type="checkbox" name="SelectedSiteAccessList[{$number}]" id="SelectedSiteAccessList[{$number}]" value="{$siteaccess}" {if $selected_siteaccess_list|contains($siteaccess)}checked="checked"{/if} /> <label for="SelectedSiteAccessList[{$number}]">{$siteaccess}</label></div>
        {/foreach}
    </div>

    <p><label for="Password">Super password:</label> <input type="password" name="Password" id="Password" /></p>

    {include uri="design:package/navigator.tpl"}

</form>