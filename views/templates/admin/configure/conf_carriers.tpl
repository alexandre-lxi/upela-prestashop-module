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


<div class="row">
    <div class="panel section8">
        <div class="row">
            <form method="POST" action="{$upela_update_carrier_link|escape:'htmlall':'UTF-8'}">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="carrier">{l s='Carrier' mod='upela'}</th>
                        <th class="offer">{l s='Offer' mod='upela'}</th>
                        <th class="from">{l s='From' mod='upela'}</th>
                        <th class="to">{l s='To' mod='upela'}</th>
                        <th class="delay">{l s='Delay' mod='upela'}</th>
                        <th class="status">{l s='Status' mod='upela'}</th>
                        <th class="edit">{l s='Edit' mod='upela'}</th>
                    </tr>
                    </thead>
                    <tbody>
                    {if isset($carriersListRelay) && $carriersListRelay && sizeof($carriersListRelay)}
                        <tr>
                            <td colspan=9><span class="accent-font"
                                                style="font-size: 16px">{l s='Relay' mod='upela'}</span></td>
                        </tr>
                        {foreach from=$carriersListRelay key=o item=offer}
                            <tr>
                                <td class="operator">{$offer.label|escape:'htmlall':'UTF-8'}</td>
                                <td class="offer">{$offer.desc_store|escape:'htmlall':'UTF-8'}</td>

                                {if $offer.is_pickup_point == 0}
                                    <td class="from">
                                        <span class="orange fa fa-home"></span>
                                        <span class="orange">{l s='On-site' mod='upela'}</span></td>
                                {else}
                                    <td class="from">
                                        <span class="blue fa fa-map-marker"></span>
                                        <span class="blue">{l s='Dropoff' mod='upela'}</span></td>
                                {/if}

                                {if $offer.is_dropoff_point == 0}
                                    <td class="from">
                                        <span class="orange fa fa-home"></span>
                                        <span class="orange">{l s='On-site' mod='upela'}</span></td>
                                {else}
                                    <td class="from">
                                        <span class="blue fa fa-map-marker"></span>
                                        <span class="blue">{l s='Dropoff' mod='upela'}</span></td>
                                {/if}


                                <td class="delay">{$offer.delay_text|escape:'htmlall':'UTF-8'}</td>

                                {if $offer.is_active == 1}
                                    <td class="status">
                                        <div class="hide">
                                            <input type="checkbox" name="offers3[]"
                                                   value="{$offer.id_service|escape:'htmlall':'UTF-8'}"
                                                   id="offer{$offer.id_service|escape:'htmlall':'UTF-8'}" {if $offer.is_active > 0} checked="checked"{/if}/>
                                        </div>
                                        <img src="../img/admin/enabled.gif" alt="true" class="toggleCarrier"
                                             onclick="UpelatoggleCarrier($(this))"></td>
                                {else}
                                    <td class="status">
                                        <div class="hide">
                                            <input type="checkbox" name="offers3[]"
                                                   value="{$offer.id_service|escape:'htmlall':'UTF-8'}"
                                                   id="offer{$offer.id_service|escape:'htmlall':'UTF-8'}" {if $offer.is_active > 0} checked="checked"{/if}/>
                                        </div>
                                        <img src="../img/admin/disabled.gif" alt="done" class="toggleCarrier"
                                             onclick="UpelatoggleCarrier($(this))">
                                    </td>
                                {/if}

                                <td class="edit">
                                    {if $offer.is_active == 1}
                                        <div class="btn-group-action">
                                            <div class="btn-group">
                                                <a href="{$carrierControllerUrl|escape:'htmlall':'UTF-8'}&id_carrier={$offer.id_carrier|escape:'htmlall':'UTF-8'}"
                                                   title="{l s='Edit' mod='upela'}" class="edit btn btn-default"
                                                   target="_blank">
                                                    <i class="icon-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    {else}
                                        <div class="disable-edit">-</div>
                                    {/if}
                                </td>
                            </tr>
                        {/foreach}
                    {/if}
                    {if isset($carriersListOthers) && $carriersListOthers && sizeof($carriersListOthers)}
                        <tr>
                            <td colspan=9><span class="accent-font"
                                                style="font-size: 16px">{l s='Standard' mod='upela'}</span></td>
                        </tr>
                        {foreach from=$carriersListOthers key=o item=offer}
                            <tr>
                                <td class="operator">{$offer.label|escape:'htmlall':'UTF-8'}</td>
                                <td class="offer">{$offer.desc_store|escape:'htmlall':'UTF-8'}</td>

                                {if $offer.is_pickup_point == 0}
                                    <td class="from">
                                        <span class="orange fa fa-home"></span>
                                        <span class="orange">{l s='On-site' mod='upela'}</span></td>
                                {else}
                                    <td class="from">
                                        <span class="blue fa fa-map-marker"></span>
                                        <span class="blue">{l s='Dropoff' mod='upela'}</span></td>
                                {/if}

                                {if $offer.is_dropoff_point == 0}
                                    <td class="from">
                                        <span class="orange fa fa-home"></span>
                                        <span class="orange">{l s='On-site' mod='upela'}</span></td>
                                {else}
                                    <td class="from">
                                        <span class="blue fa fa-map-marker"></span>
                                        <span class="blue">{l s='Dropoff' mod='upela'}</span></td>
                                {/if}


                                <td class="delay">{$offer.delay_text|escape:'htmlall':'UTF-8'}</td>

                                {if $offer.is_active == 1}
                                    <td class="status">
                                        <div class="hide">
                                            <input type="checkbox" name="offers1[]"
                                                   value="{$offer.id_service|escape:'htmlall':'UTF-8'}"
                                                   id="offer{$offer.id_service|escape:'htmlall':'UTF-8'}" {if $offer.is_active > 0} checked="checked"{/if}/>
                                        </div>
                                        <img src="../img/admin/enabled.gif" alt="true" class="toggleCarrier"
                                             onclick="UpelatoggleCarrier($(this))">
                                    </td>
                                {else}
                                    <td class="status">
                                        <div class="hide">
                                            <input type="checkbox" name="offers1[]"
                                                   value="{$offer.id_service|escape:'htmlall':'UTF-8'}"
                                                   id="offer{$offer.id_service|escape:'htmlall':'UTF-8'}" {if $offer.is_active > 0} checked="checked"{/if}/>
                                        </div>
                                        <img src="../img/admin/disabled.gif" alt="done" class="toggleCarrier"
                                             onclick="UpelatoggleCarrier($(this))"></td>
                                {/if}

                                <td class="edit">
                                    {if $offer.is_active == 1}
                                        <div class="btn-group-action">
                                            <div class="btn-group">
                                                <a href="{$carrierControllerUrl|escape:'htmlall':'UTF-8'}&id_carrier={$offer.id_carrier|escape:'htmlall':'UTF-8'}"
                                                   title="{l s='Edit' mod='upela'}" class="edit btn btn-default"
                                                   target="_blank">
                                                    <i class="icon-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    {else}
                                        <div class="disable-edit">-</div>
                                    {/if}
                                </td>
                            </tr>
                        {/foreach}
                    {/if}

                    {if isset($carriersListExpress) && $carriersListExpress && sizeof($carriersListExpress)}
                        <tr>
                            <td colspan=9><span class="accent-font"
                                                style="font-size: 16px">{l s='Express' mod='upela'}</span></td>
                        </tr>
                        {foreach from=$carriersListExpress key=o item=offer}
                            <tr>
                                <td class="operator">{$offer.label|escape:'htmlall':'UTF-8'}</td>
                                <td class="offer">{$offer.desc_store|escape:'htmlall':'UTF-8'}</td>

                                {if $offer.is_pickup_point == 0}
                                    <td class="from">
                                        <span class="orange fa fa-home"></span>
                                        <span class="orange">{l s='On-site' mod='upela'}</span></td>
                                {else}
                                    <td class="from">
                                        <span class="blue fa fa-map-marker"></span>
                                        <span class="blue">{l s='Dropoff' mod='upela'}</span></td>
                                {/if}

                                {if $offer.is_dropoff_point == 0}
                                    <td class="from">
                                        <span class="orange fa fa-home"></span>
                                        <span class="orange">{l s='On-site' mod='upela'}</span></td>
                                {else}
                                    <td class="from">
                                        <span class="blue fa fa-map-marker"></span>
                                        <span class="blue">{l s='Dropoff' mod='upela'}</span></td>
                                {/if}


                                <td class="delay">{$offer.delay_text|escape:'htmlall':'UTF-8'}</td>

                                {if $offer.is_active == 1}
                                    <td class="status">
                                        <div class="hide">
                                            <input type="checkbox" name="offers2[]"
                                                   value="{$offer.id_service|escape:'htmlall':'UTF-8'}"
                                                   id="offer{$offer.id_service|escape:'htmlall':'UTF-8'}" {if $offer.is_active > 0} checked="checked"{/if}/>
                                        </div>
                                        <img src="../img/admin/enabled.gif" alt="true" class="toggleCarrier"
                                             onclick="UpelatoggleCarrier($(this))"></td>
                                {else}
                                    <td class="status">
                                        <div class="hide">
                                            <input type="checkbox" name="offers2[]"
                                                   value="{$offer.id_service|escape:'htmlall':'UTF-8'}"
                                                   id="offer{$offer.id_service|escape:'htmlall':'UTF-8'}" {if $offer.is_active > 0} checked="checked"{/if}/>
                                        </div>
                                        <img src="../img/admin/disabled.gif" alt="done" class="toggleCarrier"
                                             onclick="UpelatoggleCarrier($(this))">
                                    </td>
                                {/if}

                                <td class="edit">
                                    {if $offer.is_active == 1}
                                        <div class="btn-group-action">
                                            <div class="btn-group">
                                                <a href="{$carrierControllerUrl|escape:'htmlall':'UTF-8'}&id_carrier={$offer.id_carrier|escape:'htmlall':'UTF-8'}"
                                                   title="{l s='Edit' mod='upela'}" class="edit btn btn-default"
                                                   target="_blank">
                                                    <i class="icon-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    {else}
                                        <div class="disable-edit">-</div>
                                    {/if}
                                </td>
                            </tr>
                        {/foreach}
                    {/if}
                    </tbody>
                </table>
                <div class="margin-form submit">
                    <div class="col-sm-offset-4">
                        <button name="processParameters" type="submit"
                                class="btn btn-primary text-center part__button">
                            {l s='Save' mod='upela'}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>