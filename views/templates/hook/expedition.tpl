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
                <tbody>
                <tr>
                    <td style="border:none; background-color: #FF6600"><img style="height: 38px; width: 38px;"
                                                                            src="{$simple_link|escape:'html':'UTF-8'}views/img/logo-upela-w.png"
                                                                            width="150px;"></td>
                    <td style="border:none; background-color: #FF6600"><span
                                style="font-weight: normal;">{$reference|escape:'html':'UTF-8'}</span></td>
                    <td style="border:none;text-align:right;background-color: #FF6600"><a class="btn btn-default"
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
