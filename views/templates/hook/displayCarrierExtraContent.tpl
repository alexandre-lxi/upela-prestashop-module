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

<div>
    <input type="hidden" name="tr_nodp" id="tr_nodp" value="{l s='Can not find a relay point!' mod='upela'}">
</div>
<div class="row" id="upela-delivery">
    <p>
            <strong id="selected-delivery-point">{l s='Search in progress...' mod='upela'}</strong>
        <br>
        <a id="choose-delivery" class="text-underline" href="#">{l s='Choose another Relay Point' mod='upela'}</a>
        <input type="hidden" value="null" name="dp_number" id="number">
        <input type="hidden" value="null" name="dp_id" id="dp_id">
        <input type="hidden" value="null" name="dp_name" id="dp_name">
        <input type="hidden" value="null" name="dp_address1" id="dp_address1">
        <input type="hidden" value="null" name="dp_address2" id="dp_address2">
        <input type="hidden" value="null" name="dp_address3" id="dp_address3">
        <input type="hidden" value="null" name="dp_postcode" id="dp_postcode">
        <input type="hidden" value="null" name="dp_city" id="dp_city">
        <input type="hidden" value="null" name="dp_country" id="dp_country">
    </p>
</div>
<div class="col-lg-12 col-md-12">
    <div id="map-upela-selected" style="width:auto; height: 300px;margin-bottom:10px"></div>
</div>
<!-- Modal -->
<div class="modal fade" id="upelaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{l s='Choose a Relay Point' mod='upela'}</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div id="map-upela" style="width:auto; height: 400px;"></div>
                    </div>

                    <div class="col-md-4">
                        <p id="no-delivery-point-found" style="display:none;">{l s='No Relay Point' mod='upela'}</p>
                        <div id="delivery-point-list">
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Close' mod='upela'}</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var url = 'https://www.upela.com/fr/order/actions.php?action=search_delivery_points&shipment_id=1212&service_id={$address.upela_service|escape:'url':'utf-8'}&postcode={$address.postcode|escape:'url':'utf-8'}&city={$address.city|escape:'url':'utf-8'}';
    var carrier_id = {$address.carrier_id|escape:'htmlall':'utf-8'};

</script>


