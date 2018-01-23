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
<style>

    .up-exp-td{
        padding-top:20px !important;
        padding-bottom:20px !important;
        padding-right:20px !important;  }
    .up-sended-td{
        padding:10% !important;
        text-align:center !important;
        vertical-align:middle !important;
    }

</style>
<script type="text/javascript">
    var infoExpedition = {$jsonShipInfo};
    var pdfImg = "{$simple_link|escape:'url':'UTF-8'}views/img/bordereau.jpg";


    $(function () {
        $('#expeditionTab').insertBefore('#myTab');
        $('#UpelaExpedition').insertAfter('#expeditionTab');
    });
    //  $('#expeditionPl').after('hr');
</script>

<div>
    <input type="hidden" name="tr_reason" id="tr_reason" value="{l s='Commercial' mod='upela'}">
    <input type="hidden" name="tr_progress" id="tr_progress" value="{l s='In progress...' mod='upela'}">
    <input type="hidden" name="tr_error1" id="tr_error1" value="{l s='An error occurred, unable to process your request' mod='upela'}">
</div>

<ul class="nav nav-tabs" id="expeditionTab">
    <li class="active">
        <a href="#shipping">
            <i class="icon-truck "></i>
            {l s='Ship with Upela' mod='upela'}
        </a>
    </li>
</ul>
<div class="tab-content panel" id="UpelaExpedition">
    <div class="tab-pane active">
        <div class="table-responsive">


            <table class="table" id="table-upela">
                <thead>
                    <tr>
                        <th style="border:none; background-color: #FF6600" colspan="3"><img
                                    style="height: 38px; width: 38px;"
                                    src="{$simple_link|escape:'html':'UTF-8'}views/img/logo-upela-w.png"
                                    width="150px;"></th>
                    </tr>
                </thead>
                {if {$is_connected|escape:'htmlall':'UTF-8'} == true}
                <tbody id="table-body-upela">
                {if !$waybill_url }
                    <tr >
                        <td class="up-exp-td">
                            <div class="form-group">
                                <label for="co" class="col-sm-4">{l s='Number of parcels' mod='upela'}</label>
                                <div class="col-sm-4">
                                    <input name="upela_count" type="text" class="form-control" id="upela_count"
                                           placeholder="1"
                                           value="{$upela_weight|escape:'htmlall':'UTF-8'}">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="wt" class="col-sm-4">{l s='Weight (Kg)' mod='upela'}</label>
                                <div class="col-sm-4">
                                    <input name="upela_weight" type="text" class="form-control" id="upela_weight"
                                           placeholder="{l s='Weight (Kg)' mod='upela'}"
                                           value="{$upela_weight|escape:'htmlall':'UTF-8'}">
                                </div>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="wt" class="col-sm-4">{l s='Length' mod='upela'}</label>
                                <div class="col-sm-4">
                                    <input name="upela_length" type="text" class="form-control" id="upela_length"
                                           placeholder="{l s='Length' mod='upela'}"
                                           value="{$upela_length|escape:'htmlall':'UTF-8'}">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="wt" class="col-sm-4">{l s='Width' mod='upela'}</label>
                                <div class="col-sm-4">
                                    <input name="upela_width" type="text" class="form-control" id="upela_width"
                                           placeholder="{l s='Width' mod='upela'}"
                                           value="{$upela_width|escape:'htmlall':'UTF-8'}">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="wt" class="col-sm-4">{l s='Height' mod='upela'}</label>
                                <div class="col-sm-4">
                                    <input name="upela_height" type="text" class="form-control" id="upela_height"
                                           placeholder="{l s='Height' mod='upela'}"
                                           value="{$upela_height|escape:'htmlall':'UTF-8'}">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="up-exp-td">
                            <div class="form-group">
                                <label for="ship_content" class="col-sm-4">{l s='Shipment content' mod='upela'}
                                    <input name="ship_content" type="text" class="form-control" id="ship_content"
                                           placeholder="{l s='Shipment content' mod='upela'}"
                                           value="{$upela_ship_content|escape:'htmlall':'UTF-8'}">
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span style="color:red;font-weight: bold;" id="upela-error">
                                {if {$paymentInfos['avalaible']|escape:'htmlall':'UTF-8'} == false}
                                    {l s='Please update your payment method!' mod='upela'}
                                {/if}
                            </span>
                        </td>
                        <td style="border:none;text-align:right;">
                            {if {$paymentInfos['avalaible']|escape:'htmlall':'UTF-8'} == false}
                                <a href="{$upela_param_link|escape:'htmlall':'UTF-8'}"
                                   class="btn btn-primary text-center part__button"
                                   target="_blank"
                                   style="text-align: center; background-color: #FF6600;">
                                    {l s='Go to payment update' mod='upela'}
                                </a>
                            {else}
                                <a id="upela-expedier" class="btn btn-primary text-center part__button"
                                   style="background-color: #FF6600" onclick="sendCommandeToUpela(infoExpedition)"
                                        > {$suivi|escape:'htmlall':'UTF-8'}</a>
                            {/if}
                        </td>
                    </tr>
                {else}
                    <tr>
                        <td class="up-sended-td">
                            <img  src="{$simple_link|escape:'htmlall':'UTF-8'}views/img/bordereau.jpg" width="150px;">
                            <br>
                            <a href="{$waybill_url|escape:'htmlall':'UTF-8'}" target="blank" class="btn btn-primary text-center part__button" style="background-color: #FF6600">Imprimer le bordereau</a>
                        </td></tr>
                {/if}
                </tbody>
                {else}
                <tbody id="table-body-upela">
                <tr>
                    <td style="border:none;" colspan="3">
                        {l s='You are not connected!' mod='upela'}
                    </td>
                </tr>
                </tbody>
                {/if}
            </table>
        </div>
    </div>
</div>
