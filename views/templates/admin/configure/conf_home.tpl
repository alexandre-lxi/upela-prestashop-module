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
                        <p>{l s='You are logged in. Welcome' mod='upela'} {$upela_firstname|escape:'htmlall':'UTF-8'}</p>
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