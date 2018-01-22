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
            <div class="col-lg-10 col-lg-offset-1">
                <div class="panel-content part__content">
                    <h2 class="text-center">{l s='How to configure your UPELA Module?' mod='upela'}  </h2>
                    <h4 class="text-center pb40">{l s='In PrestaShop' mod='upela'}</h4>
                    <div class="row pb40">
                        <div class="col-md-12">
                            <p>{l s='1) Connect to your Upela account or create one (in 2 minutes)' mod='upela'}</p>
                        </div>


                        <div class="col-md-6">
                            <a href="{$upela_register_link|escape:'htmlall':'UTF-8'}"
                               class="part__button button--white ">
                                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-14-u.png">
                                {l s='Create a Business Account' mod='upela'}
                            </a>
                        </div>
                        <div class="col-md-6">

                            <a href="{$upela_login_link|escape:'htmlall':'UTF-8'}"
                               class="part__button">
                                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-13-w.png">
                                {l s='Log Into Your Account' mod='upela'}
                            </a>

                        </div>
                    </div>

                        <div class="row pb40">
                            <div class="col-md-12 pb10">
                                <hr>
                                <p>{l s='2) Configure your account' mod='upela'}</p>
                            </div>
                            <div class="col-md-12">
                                <p>
                                    {l s='In the “Settings” tab, you will find all the information about your e-shop(s), your shipments and payment mode.' mod='upela'}
                                </p>
                                <p>
                                    <u>{l s='Shipment information:' mod='upela'}</u>
                                    {l s=' you can indicate default settings for your shipments: content, weight and dimensions. Please note that these settings will be filled in by default when you ship your orders' mod='upela'}
                                </p>
                                <br>
                                <p>
                                    <u>{l s='Payment information:' mod='upela'}</u>

                                    {l s=' click on “Modify the payment mode” to choose the payment mode that fits you the best.' mod='upela'}
                                    <br>
                                </p>
                                <ul class="part__list">
                                    <li>
                                        <i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                        {l s='SEPA: follow the 3 steps of validation, don’t forget to upload the 3 files (SEPA mandate, K-bis and RIB) in the step 3.' mod='upela'}
                                    </li>
                                </ul>

                            </div>
                        </div>
                            <div class="row pb40">
                                <div class="col-md-12 pb10">

                                    <hr>
                                    <p>{l s='3) Configure your carriers' mod='upela'}</p>
                                </div>
                                <div class="col-md-12">
                                    <ul class="part__list">
                                        <li class=" pb10">
                                            <i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                            {l s='In the “Carriers” tab, select the carriers you want to show on your e-shop. The carriers are organized by delivery mode: Drop-off, Standard or Express.' mod='upela'}
                                        </li>
                                        <li class=" pb10">
                                            <i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                            {l s='Select or unselect the carrier you want to activate on your shop in the “Status” column. Click on “Configure”.' mod='upela'}
                                        </li>
                                        <li class=" pb10">
                                            <i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                            {l s='The settings of the carrier you selected are shown on a new tab. In the tab “2 Destinations of shipments and fares”, the shipments fares are automatically applied from the default settings you saved on step 2.' mod='upela'}
                                            <br><br>
                                            <small class="light-accent-font">
                                                {l s='To modify the fares or list of destinations, you just have to select or unselect the destinations and indicate the fares you want to show on your shop.' mod='upela'}
                                            </small>
                                        </li>
                                        <li class=" pb10">
                                            <i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                            {l s='In the tab “3 Height, weight and associated groups”, fill in the maximum dimensions of the parcels you want to ship with this carrier.' mod='upela'}
                                        </li>
                                        <li class=" pb10">
                                            <i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                            {l s='Click on “Finish” to finalize the configuration of your carrier.' mod='upela'}
                                            <br>
                                            <br>
                                            <small class="light-accent-font">{l s='If you want to add more carriers, repeat step 3.' mod='upela'}
                                            </small>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row pb40">
                                <div class="col-md-12 pb10 light-accent-font">
                                    <hr>
                                    <p class="light-accent-font">{l s='4) Ship in 2 clicks' mod='upela'}</p>
                                </div>
                                <div class="col-md-12">
                                    <ul class="part__list">
                                        <li class=" pb10">
                                            <i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                            {l s='From the PrestaShop menu, click on “Orders”. The list of all the orders made on your shop appears. Click on the order you want to send.' mod='upela'}
                                        </li>
                                        <li class=" pb10">
                                            <i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                            {l s='In the “Ship with Upela” section, your shipment default settings are already filled, you only have to indicate the content of your parcel. Click on “Send”.' mod='upela'}
                                        </li>
                                        <li class=" pb10">
                                            <i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                            {l s='The shipping label will appear below, you only have to print it and paste it on your parcel!' mod='upela'}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                    <h2 class="text-center">{l s='Congratulations, your module is now ready!' mod='upela'}  </h2>
                    <h4 class="text-center">{l s='Upela, the best way to ship your parcels.' mod='upela'}  </h4>
                        </div>
            </div>
        </div>
    </div>
</div>

