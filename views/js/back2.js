/**
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
 */

$(document).ready(function () {
    var UpelaStoreModuleController = new UpelaStoreBOController();
    UpelaStoreModuleController.init();
});

var UpelaStoreBOController = function () {

    /* Account Creation Selectors */
    this.wizard_store = '#upela_store_wizard';
    this.formStoreCreation = '#storeCreationForm';

    this.init = function () {
        $(this.wizard_store).wizard();
        $(this.wizard_store).wizard('next', 'foo');
        $(this.wizard_store).wizard('next', 'foo');

        this.bindEvents();
    };

    this.bindEvents = function () {
        var _this = this;
        $(this.wizard_store).on('finished.fu.wizard', function (evt, data) {
            $(_this.formStoreCreation).submit();
        });
    };
};

$(document).ready(function () {
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var currentTab = $(e.target).text(); // get current tab
        $(".current-tab span").html(currentTab);
    });
});