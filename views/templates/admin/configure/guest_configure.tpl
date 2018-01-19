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
        {if {$upela_login|escape:'htmlall':'UTF-8'} ==true || {$carrier_select|escape:'htmlall':'UTF-8'}==true}
        <li class="nav-item">
            {else}
        <li class="nav-item active">
            {/if}
            <a href="#home_form" data-toggle="tab" role="tab">
                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/logo-upela.png"/>
                {l s='The Upela solution' mod='upela'}
            </a>
        </li>
        {if {$upela_login|escape:'htmlall':'UTF-8'}==true}
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
    <div class="tab-pane {if {$upela_login|escape:'htmlall':'UTF-8'} ==false && {$carrier_select|escape:'htmlall':'UTF-8'}==false}active{/if}"
         id="home_form"
         role="tabpanel">
        <div class="row col-lg-12 col-md-12">
            <div class="upela_home_form_bg">
                <div class="container">
                    <div class="upela_home_caption">
                        <h1>{l s='Upela' mod='upela'}</h1>
                        <h2>{l s='The best way to ship a parcel' mod='upela'}</h2>
                        <p>{l s='Compare quotes and ship with ease from a single platform.' mod='upela'}</p>
                    </div>
                    <div class="row pb10">
                        {if $upela_user_connected}
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 cadre">
                                <p>{l s='You are logged in. Welcome' mod='upela'} {$upela_username|escape:'htmlall':'UTF-8'}</p>
                                <a href="" onClick="this.href='{$upela_user_link|escape:'htmlall':'UTF-8'}'"
                                   class="part__button" target="_blank">
                                    {l s='Go to Upela.com' mod='upela'}
                                    <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-06-w.png"
                                         alt="Upela - icon">
                                </a>
                            </div>
                        {else}
                            <p>{l s='Not logged in yet!' mod='upela'}</p>
                            <a href="{$upela_register_link|escape:'htmlall':'UTF-8'}"
                               class="part__button button--white">
                                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-14-u.png">
                                {l s='Create a Business Account' mod='upela'}
                            </a>
                            <a href="{$upela_login_link|escape:'htmlall':'UTF-8'}"
                               class="part__button">
                                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-13-w.png">
                                {l s='Log Into Your Account' mod='upela'}
                            </a>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
        <div class="row col-lg-12 col-md-12 pt10">
            <p class="bg">{l s='With Upela and Prestashop, get negotiated rates on all your shipments: consignments, parcels, pallets.' mod='upela'}</p>
            <br>
        </div>

        <div class="row col-lg-12 col-md-12">
            <div class="panel ">
                <div class="panel-content text-center">
                    <div class="row">
                        <h2 class="light">{l s='Our carriers' mod='upela'}</h2>
                        <br>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/carriers/logo-transporteurs-FR-ES-IT.jpg"
                                 class="upela_logo_img" alt="Carriers">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row col-lg-12 col-md-12">
            <div class="panel">
                <div class="panel-content text-center">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="section3Img">
                                <div class="align-img-center">
                                    <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-23.png"
                                         alt="icon-23">
                                </div>
                            </div>
                            <p>{l s='The reliability of the leaders in transport' mod='upela'}</p>
                        </div>
                        <div class="col-sm-3">
                            <div class="section3Img">
                                <div class="align-img-center">
                                    <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-24.png"
                                         alt="icon-24">
                                </div>
                            </div>
                            <p>{l s='Ultra-flexible delivery times within 3-hour / next-day / two-days' mod='upela'}</p>
                        </div>
                        <div class="col-sm-3">
                            <div class="section3Img">
                                <div class="align-img-center">
                                    <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-25.png"
                                         alt="icon-25">
                                </div>
                            </div>
                            <p>{l s='A Multi-Carrier Customer Service dedicated to our business customers' mod='upela'}</p>
                        </div>
                        <div class="col-sm-3">
                            <div class="section3Img">
                                <div class="align-img-center">
                                    <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-26.png"
                                         alt="icon-26">
                                </div>
                            </div>
                            <p>{l s='Free eCommerce shipping modules to make your logistic easier' mod='upela'}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="section3Img">
                                <div class="align-img-center">
                                    <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-27.png"
                                         alt="icon-27">
                                </div>
                            </div>
                            <p>{l s='Integrated customs service' mod='upela'}</p>
                        </div>
                        <div class="col-sm-4">
                            <div class="section3Img">
                                <div class="align-img-center">
                                    <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-28.png"
                                         alt="icon-28">
                                </div>
                            </div>
                            <p>{l s='No minimum invoicing amount' mod='upela'}</p>
                        </div>
                        <div class="col-sm-4">
                            <div class="section3Img">
                                <div class="align-img-center">
                                    <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-29.png"
                                         alt="icon-29">
                                </div>
                            </div>
                            <p>{l s='Time Saver: ship in a few clicks' mod='upela'}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row col-lg-12 col-md-12">
            <div class="panel">
                <div class="panel-content text-center">
                    <div class="row">
                        <h2 class="light">
                            <span class="orange fontBlack"> {l s='Upela' mod='upela'}</span> {l s='in 3 clicks' mod='upela'}
                        </h2>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="section4Img">
                                    <div class="align-img-center">
                                        <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-30.png"
                                             alt="Comparer">
                                    </div>
                                </div>
                                <p>{l s='Compare' mod='upela'}</p>
                            </div>
                            <div class="col-sm-4">
                                <div class="section4Img">
                                    <div class="align-img-center">
                                        <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-31.png"
                                             alt="Expédier">
                                    </div>
                                </div>
                                <p>{l s='Ship' mod='upela'}</p>
                            </div>
                            <div class="col-sm-4">
                                <div class="section4Img">
                                    <div class="align-img-center"><img
                                                src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-32.png"
                                                alt="Suivre"></div>
                                </div>
                                <p>{l s='Track' mod='upela'}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h2 class="light"><span
                                    class="orange fontBlack"> {l s='Upela' mod='upela'}</span> {l s='in video'
                            mod='upela'}</h2>
                        <div class="row text-center video">
                            <iframe src="https://www.youtube.com/embed/xhAKCNm-IZY"
                                    frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row col-lg-12 col-md-12">
            <div class="panel section8">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h2 class="light">{l s='Our eCommerce shipping modules' mod='upela'}</h2>
                            <p>{l s='Gain in productivity and competitiveness' mod='upela'}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <ul class="list">
                                <li><i class="fa fa-caret-right listStyle" aria-hidden="true"></i>{l s='Plug and Play'
                                    mod='upela'}
                                </li>
                                <li><i class="fa fa-caret-right listStyle"
                                       aria-hidden="true"></i>{l s='Reduce input errors' mod='upela'}
                                </li>
                                <li><i class="fa fa-caret-right listStyle"
                                       aria-hidden="true"></i>{l s='Automatic tracking updates' mod='upela'}
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-push-4 col-sm-4">
                            <ul class="list">
                                <li><i class="fa fa-caret-right listStyle"
                                       aria-hidden="true"></i>{l s='Integrated logistics' mod='upela'}
                                </li>
                                <li><i class="fa fa-caret-right listStyle"
                                       aria-hidden="true"></i>{l s='Centralized orders' mod='upela'}
                                </li>
                                <li><i class="fa fa-caret-right listStyle"
                                       aria-hidden="true"></i>{l s='Multi-warehouse management' mod='upela'}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row col-lg-12 col-md-12">
            <div class="panel section8">
                <div class="panel-content">
                    <div class="orangeBorder">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row text-center">
                                    <div class="col-sm-6">
                                        <div class="section9Img">
                                            <div class="align-img-center">
                                                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-36.png"
                                                     alt="Chat">
                                            </div>
                                        </div>
                                        <p>{l s='Online chat' mod='upela'}</p></div>
                                    <div class="col-sm-6">
                                        <div class="section9Img">
                                            <div class="align-img-center">
                                                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-37.png"
                                                     alt="Téléphone">
                                            </div>
                                        </div>
                                        <p>{l s='Phone' mod='upela'}</p></div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-sm-6">
                                        <div class="section9Img">
                                            <div class="align-img-center">
                                                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-38.png"
                                                     alt="Online">
                                            </div>
                                        </div>
                                        <p>{l s='Online' mod='upela'}</p></div>
                                    <div class="col-sm-6">
                                        <div class="section9Img">
                                            <div class="align-img-center">
                                                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-39.png"
                                                     alt="Mail">
                                            </div>
                                        </div>
                                        <p>{l s='Email' mod='upela'}</p></div>
                                </div>
                            </div>
                            <div class="col-sm-6"><h2
                                        class="mt10 light orange fontBlack">{l s='The UPELA Customer Support' mod='upela'}
                                    </span></h2>
                                <p>{l s='A Multi-Carrier Customer Service' mod='upela'}</p>
                                <ul class="list">
                                    <li><i class="fa fa-caret-right listStyle"
                                           aria-hidden="true"></i>{l s='A reply the same day' mod='upela'}
                                    </li>
                                    <li><i class="fa fa-caret-right listStyle"
                                           aria-hidden="true"></i>{l s='Agents available at all times' mod='upela'}
                                    </li>
                                    <li><i class="fa fa-caret-right listStyle"
                                           aria-hidden="true"></i>{l s='Shipment tracking and centralized management of the Customer Service' mod='upela'}
                                    </li>
                                    <li><i class="fa fa-caret-right listStyle"
                                           aria-hidden="true"></i>{l s='Examine your specific cases' mod='upela'}
                                    </li>
                                    <li><i class="fa fa-caret-right listStyle"
                                           aria-hidden="true"></i>{l s='Assist you and guide you in your various projects' mod='upela'}
                                    </li>
                                    <li><i class="fa fa-caret-right listStyle"
                                           aria-hidden="true"></i>{l s='Discover our tools and multiple services' mod='upela'}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane {if {$carrier_select|escape:'htmlall':'UTF-8'}==true}active{/if}" id="carriers_form"
         role="tabpanel">
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
    </div>


    <div class="tab-pane" id="guide_form" role="tabpanel">
        <div class="row">
            <div class="panel section8">
                <div class="row">
                    <div class="col-lg-10 col-lg-offset-1">
                        <div class="panel-content part__content">
                            <h2 class="text-center">{l s='How to set up your UPELA account?' mod='upela'} </h2>
                            <h4 class="text-center pb40">{l s='In your Prestashop store' mod='upela'}</h4>
                            <div class="row pb40">
                                <div class="col-md-8">
                                    <p>{l s='3) Register or log in to your Upela account' mod='upela'}</p>
                                </div>
                                <div class="col-md-4">
                                    {if !$upela_user_connected}
                                        <a href="{$upela_register_link|escape:'htmlall':'UTF-8'}"
                                           class="part__button button--white">
                                            <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-14-u.png">
                                            {l s='Create a Business Account' mod='upela'}
                                        </a>
                                        <a href="{$upela_login_link|escape:'htmlall':'UTF-8'}"
                                           class="part__button">
                                            <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-13-w.png">
                                            {l s='Log Into Your Account' mod='upela'}
                                        </a>
                                        <br>
                                    {/if}
                                </div>
                            </div>
                            <div class="row pb40">
                                <div class="col-md-8">
                                    <p>{l s='4) Open Upela directly from your store' mod='upela'}</p>
                                </div>
                                <div class="col-md-4">
                                    <a href="" onClick="this.href='{$upela_user_link|escape:'htmlall':'UTF-8'}'"
                                       class="part__button" target="_blank">
                                        {l s='Go to Upela.com' mod='upela'}
                                        <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-06-w.png"
                                             alt="Upela - icon">
                                    </a>
                                </div>

                            </div>
                            <h4 class="text-center pb40">{l s='In your Upela Customer Area, \"My Stores\" menu' mod='upela'}</h4>
                            <div class="row pb40">
                                <div class="col-md-6">
                                    <p>{l s='1) All orders placed on your eShops are automatically imported with their status' mod='upela'}</p>
                                    <ul class="part__list">
                                        <li><i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                            {l s='You' mod='upela'} <span
                                                    class="light-accent-font">{l s='generate' mod='upela'}</span> {l s='the shipping labels and ' mod='upela'}
                                            <span class="light-accent-font">{l s='ship' mod='upela'}</span> {l s='your parcels.' mod='upela'}
                                        </li>
                                        <li><i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                            {l s='Our shipping module automatically updates your respective stores with the new order status (sent) and  the tracking number.' mod='upela'}
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-md-6">
                                    <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/manual-screen3.png"
                                         class="part__img">
                                </div>
                            </div>
                            <div class="row pb40">

                                <p>{l s='2) Ship your order' mod='upela'}</p>
                                <ul class="part__list">
                                    <li><i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                        {l s='The shipping and delivery addresses are automatically pre-filled' mod='upela'}
                                    </li>
                                    <li><i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                        {l s='Complete the information about your sending, including the description of your parcels (quantity, unit weight, dimension)' mod='upela'}
                                    </li>
                                    <li><i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                        {l s='Click on \"Compare the offers\" to finalize your order' mod='upela'}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane {if {$upela_login|escape:'htmlall':'UTF-8'}==true}active{/if}" id="settings_form"
         role="tabpanel">
        {if $upela_user_connected}
            <div class="panel">
                <div class="row">
                    <div class="panel-content part__content">
                        <div class="col-lg-6">
                            <h2 class="col-lg-offset-1">{l s='Shipment information' mod='upela'}</h2>
                            <br>
                            <form method='POST' action="{$upela_parameters_link|escape:'htmlall':'UTF-8'}"
                                  class="form-horizontal">
                                <h4>{l s='Default parcel informations' mod='upela'}</h4>
                                <div class="form-group">
                                    <label for="ship_content"
                                           class="col-sm-4">{l s='Shipments content' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="ship_content" type="text" class="form-control" id="ship_content"
                                               placeholder="{l s='Shipment content' mod='upela'}"
                                               value="{$upela_ship_content|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="wt" class="col-sm-4">{l s='Weight (Kg)' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="upela_weight" type="text" class="form-control" id="upela_weight"
                                               placeholder="{l s='Weight (Kg)' mod='upela'}"
                                               value="{$upela_weight|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                                <h5>{l s='Dimensions (cm)' mod='upela'}</h5>
                                <div class="form-group">
                                    <label for="wt" class="col-sm-4">{l s='Length' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="upela_length" type="text" class="form-control" id="upela_length"
                                               placeholder="{l s='Length' mod='upela'}"
                                               value="{$upela_length|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="wt" class="col-sm-4">{l s='Width' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="upela_width" type="text" class="form-control" id="upela_width"
                                               placeholder="{l s='Width' mod='upela'}"
                                               value="{$upela_width|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="wt" class="col-sm-4">{l s='Height' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="upela_height" type="text" class="form-control" id="upela_height"
                                               placeholder="{l s='Height' mod='upela'}"
                                               value="{$upela_height|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-sm-offset-4">
                                        <button name="processParameters" type="submit"
                                                class="btn btn-primary text-center part__button button--white">

                                            {l s='Save' mod='upela'}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <h2 class="col-lg-offset-1">{l s='Payment information' mod='upela'}</h2>
                            <br>
                            {if {$paymentInfos['info']|escape:'htmlall':'UTF-8'} == false}
                                <h4>{l s='No payment informations avalaible !' mod='upela'}</h4>
                            {else}
                                {if {$paymentInfos['avalaible']|escape:'htmlall':'UTF-8'} == false}
                                    <h4>
                                        {l s='You can not ship your orders directly. You must switch your account to SEPA payment or credit your account!' mod='upela'}
                                    </h4>
                                    <br>
                                {/if}
                                <table class="table">
                                    <tr>
                                        <td style="font-size: 16px">
                                            {l s='Payment method' mod='upela'}
                                        </td>
                                        <td style="font-size: 16px">
                                            {$paymentInfos['method']|escape:'htmlall':'UTF-8'}
                                        </td>
                                    </tr>
                                    {if {$paymentInfos['method']|escape:'htmlall':'UTF-8'}=='Credit card'}
                                        <tr>
                                            <td style="font-size: 16px">
                                                {l s='Amount avalaible' mod='upela'}
                                            </td>
                                            <td style="font-size: 16px">
                                                {$paymentInfos['amount']|escape:'htmlall':'UTF-8'}
                                            </td>
                                        </tr>
                                    {/if}
                                    {if {$paymentInfos['voucher']|escape:'htmlall':'UTF-8'}==true}
                                        <tr>
                                            <td style="font-size: 16px">
                                                {l s='Voucher amount' mod='upela'}
                                            </td>
                                            <td style="font-size: 16px">
                                                {$paymentInfos['vamount']|escape:'htmlall':'UTF-8'}
                                            </td>
                                        </tr>
                                    {/if}
                                </table>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <div class="col-lg-offset-1">
                                    <div class="row">
                                        <div class="col-sm-offset-3">
                                            <a href="{$upela_param_link|escape:'htmlall':'UTF-8'}"
                                               class="part__button button--white"
                                               style="text-align: center;">
                                                {l s='Go to payment update' mod='upela'}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            {/if}

                        </div>
                    </div>
                </div>
            </div>
        {/if}
        <div class="panel">
            <div class="row">
                <div class="panel-content part__content">
                    <div class="col-lg-12">
                        <h2>{l s='Connection parameters to your Upela account' mod='upela'}</h2>
                        <br>
                        <div class="col-lg-6">
                            {if $upela_user_connected}
                                <h4>{l s='Your account has been activated.' mod='upela'}</h4>
                                <br>
                                <form method='POST' action="#"
                                      class="form-horizontal col-lg-offset-1">
                                    <div class="form-group">
                                        <label for="email" class="col-sm-4">{l s='Email' mod='upela'}</label>
                                        <div class="col-sm-6">
                                            <input name="upela_email" type="email" class="form-control" id="email"
                                                   placeholder="{l s='Email' mod='upela'}" disabled
                                                   value="{$upela_user_email|escape:'htmlall':'UTF-8'}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="col-sm-4">{l s='Password' mod='upela'}</label>
                                        <div class="col-sm-6">
                                            <input name="upela_password" type="password" class="form-control"
                                                   id="password"
                                                   placeholder="{l s='Password' mod='upela'}" disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-sm-offset-3">
                                                <button name="updateLogin" type="submit"
                                                        class="btn btn-primary text-center part__button button--white">
                                                    <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-13.png">
                                                    {l s='Disconnect' mod='upela'}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            {else}
                                <form method='POST' action="{$upela_login_link|escape:'htmlall':'UTF-8'}"
                                      class="form-horizontal col-lg-offset-1">
                                    <div class="form-group">
                                        <label for="email" class="col-sm-4">{l s='Email' mod='upela'}</label>
                                        <div class="col-sm-6">
                                            <input name="upela_email" type="email" class="form-control" id="email"
                                                   placeholder="{l s='Email' mod='upela'}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="col-sm-4">{l s='Password' mod='upela'}</label>
                                        <div class="col-sm-6">
                                            <input name="upela_password" type="password" class="form-control"
                                                   id="password"
                                                   placeholder="{l s='Password' mod='upela'}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-sm-offset-1">
                                                <button name="processLogin" type="submit"
                                                        class="btn btn-primary text-center part__button">
                                                    <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-13-w.png">
                                                    {l s='Log Into your Account' mod='upela'}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-sm-offset-1">
                                            <a href="{$upela_register_link|escape:'htmlall':'UTF-8'}"
                                               class="part__button button--white">
                                                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-14-u.png">
                                                {l s='Create a Business Account' mod='upela'}
                                            </a>
                                        </div>
                                    </div>

                                </form>
                            {/if}
                        </div>
                        <div class="col-lg-6">
                            <h4 class="col-lg-offset-1">{l s='Stores information' mod='upela'}</h4>
                            <br>
                            <h4 class="col-lg-offset-1">{l s='You currently have:' mod='upela'} {$upela_nbstores|escape:'htmlall':'UTF-8'} {l s='store(s)' mod='upela'}</h4>
                            <h4 class="col-lg-offset-1">{l s='You currently have:' mod='upela'} {$upela_storeexsists|escape:'htmlall':'UTF-8'} {l s='Prestashop store(s)' mod='upela'}</h4>
                            <br>
                            {if $upela_user_connected}
                                <div class="col-lg-offset-1">
                                    <div class="row">
                                        <div class="col-sm-offset-3">
                                            <a href="{$upela_store_link|escape:'htmlall':'UTF-8'}"
                                               class="part__button button--white"
                                               style="text-align: center;">
                                                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-40.png">
                                                {l s='Create store' mod='upela'}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="tab-pane" id="contact_form" role="tabpanel">
        <div class="row">
            <div class="panel">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel-content part__content text-center">
                            <h2 class="part__title">{l s='Upela Customer Service' mod='upela'}</h2>
                            <h4>{l s='Contact the support Upela' mod='upela'}</h4>
                            <a href="{$upela_link_support|escape:'htmlall':'UTF-8'}"
                               target="_blank">{l s='Support' mod='upela'}</a>
                            <br>
                            <img class="part__icon" src="{$_path|escape:'htmlall':'UTF-8'}views/img/logocontact.png"
                                 alt="Upela - image"/>
                            <p>{l s='MPG UPELA' mod='upela'}</p>
                            <p>{l s='17 RUE DE SURENE' mod='upela'}</p>
                            <p>{l s='75008 PARIS - FRANCE' mod='upela'}</p>
                            <p>{l s='RCS Paris 750 389 769' mod='upela'}</p>
                            <p>{l s='N° TVA FR12750389769' mod='upela'}</p>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
