{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* @author    UPELA
* @copyright 2017-2018 MPG Upela
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

{if isset($postSuccess)}
    {foreach from=$postSuccess item=ps}
        <div class="alert alert-success">{$ps|escape:'htmlall':'UTF-8'}</div>
    {/foreach}
{/if}

{if isset($postErrors)}
    {foreach from=$postErrors item=pe}
        <div class="alert alert-danger">{$pe|escape:'htmlall':'UTF-8'}</div>
    {/foreach}
{/if}
{if isset($postInfos)}
    {foreach from=$postInfos item=pe}
        <div class="alert alert-info">{$pe|escape:'htmlall':'UTF-8'}</div>
    {/foreach}
{/if}

{if isset($modeform) && !$upela_user_connected}
    {$modeform}
{/if}

<div id="upela_mnu">
    <ul class="nav nav-tabs" id="upelaTabs">
        {if {$param_select|escape:'htmlall':'UTF-8'} ==true || {$carrier_select|escape:'htmlall':'UTF-8'}==true}
        <li class="nav-item">
            {else}
        <li class="nav-item active">
            {/if}
            <a href="#home_form" data-toggle="tab" role="tab">
                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/logo-upela.png"/>
                {l s='The Upela solution' mod='upela'}
            </a>
        </li>
        {if {$param_select|escape:'htmlall':'UTF-8'}==true}
        <li class="nav-item active">
            {else}
        <li class="nav-item">
            {/if}
            <a href="#settings_form" data-toggle="tab" role="tab">
                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-config.png"/>
                {l s='Parameters' mod='upela'}
            </a>
        </li>
        {if {$upela_user_connected|escape:'htmlall':'UTF-8'}==true}
            {if {$carrier_select|escape:'htmlall':'UTF-8'}==true}
                <li class="nav-item active">
                    {else}
                <li class="nav-item">
            {/if}
            <a href="#carriers_form" data-toggle="tab" role="tab">
                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-carriers.png"/>
                {l s='Carriers' mod='upela'}
            </a>
            </li>
        {/if}

        {if {$isnotpsready|escape:'htmlall':'UTF-8'}==true}
            <li class="nav-item">
                <a href="#guide_form" data-toggle="tab" role="tab">
                    <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-guide.png"/>
                    {l s='User manual' mod='upela'}
                </a>
            </li>
            <li class="nav-item">
                <a href="#contact_form" data-toggle="tab" role="tab">
                    <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-contact.png"/>
                    {l s='Contact' mod='upela'}
                </a>
            </li>
        {/if}
    </ul>
</div>

<div id="upela_content" class="tab-content">
    <div class="tab-pane {if {$param_select|escape:'htmlall':'UTF-8'} ==false && {$carrier_select|escape:'htmlall':'UTF-8'}==false}active{/if}"
         id="home_form"
         role="tabpanel">
        {include file="$tpl_home"}
    </div>

    <div class="tab-pane {if {$carrier_select|escape:'htmlall':'UTF-8'}==true}active{/if}" id="carriers_form"
         role="tabpanel">
        {include file="$tpl_carriers"}
    </div>
    <div class="tab-pane" id="guide_form" role="tabpanel">
        {include file="$tpl_guide"}
    </div>
    <div class="tab-pane {if {$param_select|escape:'htmlall':'UTF-8'}==true}active{/if}" id="settings_form"
         role="tabpanel">
        {include file="$tpl_params"}
    </div>
    <div class="tab-pane" id="contact_form" role="tabpanel">
        {include file="$tpl_contact"}
    </div>

</div>


<script type="text/javascript">

    function UpelatoggleCarrier(carrier) {
        Upela_modify = true;
        var value = carrier.attr('alt');


        var prices = carrier.parents('tr').find('.price').children('div');
        var checkbox = carrier.parent('td').find('input');

        if (value === 'true') {
            //prices.fadeOut();
            carrier.attr('alt', 'false');
            checkbox.attr('checked', false);
            carrier.attr('src', '../img/admin/disabled.gif');
        } else {
            carrier.attr('alt', 'true');
            checkbox.attr('checked', true);
            carrier.attr('src', '../img/admin/enabled.gif');
        }


    }
</script>
