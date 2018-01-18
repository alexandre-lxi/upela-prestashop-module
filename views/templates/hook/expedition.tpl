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

</style>
<script type="text/javascript">$(function () {
        $('#expeditionTab').insertBefore('#myTab');
        $('#UpelaExpedition').insertAfter('#expeditionTab');
    });
    //  $('#expeditionPl').after('hr');
</script>
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

            <table class="table">
                <thead>
                <tr>

                    <th style="border:none; background-color: #FF6600" colspan="4"><img
                                style="height: 38px; width: 38px;"
                                src="{$simple_link|escape:'html':'UTF-8'}views/img/logo-upela-w.png"
                                width="150px;"></th>
                </tr>

                </thead>
                <tbody>
                <tr >
                    <td class="up-exp-td">
                        <div class="form-group">
                            <label for="wt" class="col-sm-4">{l s='Weight (Kg)' mod='upela'}</label>
                            <div class="col-sm-4">
                                <input name="upela_weight" type="text" class="form-control" id="upela_weight"
                                       placeholder="{l s='Weight (Kg)' mod='upela'}"
                                       value="{$upela_weight|escape:'htmlall':'UTF-8'}">
                            </div>
                        </div>
                    </td>
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
                    <td colspan="3"></td>
                    <td style="border:none;text-align:right;">
                        <a class="btn btn-primary text-center part__button"
                           style="background-color: #FF6600"
                           target="{$target|escape:'html':'UTF-8'}"
                           href="{$link_suivi|escape:'html':'UTF-8'}"><i
                                    class="{$iconBtn|escape:'html':'UTF-8'}"></i> {$suivi|escape:'html':'UTF-8'}</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
