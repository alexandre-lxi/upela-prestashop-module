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
    var UpelaAccountModuleController = new UpelaAccountBOController();
    UpelaAccountModuleController.init();

});

var UpelaAccountBOController = function () {

    /* Account Creation Selectors */
    this.wizard = '#upela_account_wizard';
    this.formAccountCreation = '#accountCreationForm';

    this.init = function () {
        if ($(this.wizard).length) {
            $(this.wizard).wizard();
        }
        this.bindEvents();
    };

    this.bindEvents = function () {
        var _this = this;
        $(this.wizard).on('finished.fu.wizard', function (evt, data) {
            $(_this.formAccountCreation).submit();
        });
    };
};


$(document).ready(function () {
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var currentTab = $(e.target).text(); // get current tab
        $(".current-tab span").html(currentTab);
    });
});