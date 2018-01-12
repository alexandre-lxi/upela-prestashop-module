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

<div class="row" id="upela-delivery">
<p>
    <small id="selected-delivery-point">Recherche en cours ...</small><br>
    <a id="choose-delivery" class="text-underline" href="#" >Choisir un autre Point Relais</a>
    <input type="hidden" value="null" name="dp_number" id="number" >
    <input type="hidden" value="null" name="dp_id" id="dp_id" >
    <input type="hidden" value="null" name="dp_name" id="dp_name">
    <input type="hidden" value="null" name="dp_address1" id="dp_address1">
    <input type="hidden" value="null" name="dp_address2" id="dp_address2">
    <input type="hidden" value="null" name="dp_address3" id="dp_address3">
    <input type="hidden" value="null" name="dp_postcode" id="dp_postcode">
    <input type="hidden" value="null" name="dp_city" id="dp_city">
    <input type="hidden" value="null" name="dp_country" id="dp_country">
</p>
</div>
<!-- Modal -->
<div class="modal fade" id="upelaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Trouver un point relais</h5>
            </div>
            <div class="modal-body">
               <div class="row">
                   <div class="col-md-8">
                       <div id="map-upela" style="width:auto; height: 400px;"></div>
                   </div>

                   <div class="col-md-4">
                       <p id="no-delivery-point-found" style="display:none;">Aucun point relais</p>
                       <div id="delivery-point-list">
                       </div>
                   </div>

               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>


    <script type="text/javascript">

        var url = 'https://www.upela.com/fr/order/actions.php?action=search_delivery_points&shipment_id=1212&service_id={$address.upela_service}&postcode={$address.postcode}&city={$address.city}';
        var carrier_id = {$address.carrier_id};

    </script>

