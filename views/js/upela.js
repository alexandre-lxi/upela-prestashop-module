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
 *
 */

var map;
var markers = [];
var infoWindow;
var first = false;
var listDroppOff = false;

if (typeof carrier_id === 'undefined') {
    var carrier_id = false;
}

if (typeof carrier_id_parent === 'undefined') {
    var carrier_id_parent = false;
}

function hideOffer($idCarrier) {
    $('#delivery_option_' + $idCarrier).hide();
}

function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }

    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";


    var url = '/index.php?fc=module&module=upela&controller=ajax&option=setDropOff';

    if (typeof value !== 'undefined' && value !== '') {
        value2 = JSON.parse(value);
        value2.hours_html = '';
        $.ajax({
            url: url,
            type: 'POST',
            data: value2,
            success: function (s) {
                document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
            },
            error: function () {
                console.log('unable to set dropoff location');
            }
        })
    }

}

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0)
            return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function initializeMap(options) {
    var uluru = {lat: options.lat, lng: options.lng};
    map = new google.maps.Map(document.getElementById(options.id), {
        zoom: options.zoom,
        center: uluru
    });
    infoWindow = new google.maps.InfoWindow();
    google.maps.event.trigger(map, 'resize');
}

function setSelectedVal(dropoffLocation) {
    $('#dp_id').val(dropoffLocation.dropoff_location_id);
    $('#dp_number').val(dropoffLocation.number);
    $('#dp_name').val(dropoffLocation.name);
    $('#dp_address1').val(dropoffLocation.address1);
    $('#dp_address2').val(dropoffLocation.address2);
    $('#dp_postcode').val(dropoffLocation.postcode);
    $('#dp_city').val(dropoffLocation.city);
    $('#dp_country').val(dropoffLocation.country_code);
    var info = dropoffLocation.name + ', ' + dropoffLocation.address1 + ' ' + dropoffLocation.address2 + ' ' + dropoffLocation.city;
    $('#selected-delivery-point').html(info);
}

function createMarker(latlng, data) {
    var html = '<div class="marker_info">'
        + '<p><strong>' + data.name + '</strong></p>'
        + '<p>' + data.address1 + '<br/>' + data.postcode + '&nbsp;' + data.city + '</p>'
        + (data.hours_html && data.hours_html.length ?
            '<div><strong>Horaires </strong><br/>' + data.hours_html + '</div>' : '')
        + '<div class="clear"></div>'
        + '</div>';
    var markerData = {
        map: map,
        position: latlng
    };
    if (data.number && data.number >= 1 && data.number <= 10) {
        markerData.icon = 'https://www.upela.com/images/markers/marker-' + data.number + '.png';
    }
    var marker = new google.maps.Marker(markerData);
    closeInfoWindow = function () {
        infoWindow.close();
    };
    google.maps.event.addListener(marker, 'click', function () {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
    });
    google.maps.event.addListener(map, 'click', closeInfoWindow);
    markers.push(marker);
}

function setDropOffPoints(dropOfflist) {
    var html = [], idx = 0;
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0; i < dropOfflist.length; i++) {
        var row = dropOfflist[i];
        var latlng = new google.maps.LatLng(
            parseFloat(row.latitude),
            parseFloat(row.longitude));
        createMarker(latlng, row);
        bounds.extend(latlng);
        var number = (i + 1);
        var selection = parseInt($('#dp_number').val());
        html[idx++] = '<div id="delivery-point-' + number + '" class="delivery-point">'
            + '<div class="delivery-point-name"><img src="https://www.upela.com/images/markers/marker-' + row.number + '.png" />&nbsp;<a class="upela-marker-click" href="#" data-href="' + i + '">' + (row.name || '') + '</a></div>'
            + '<p>' + row.address1 + '<br/>' + row.postcode + '&nbsp;' + row.city
            + (selection == 1 ? '<br/><a id="select-delivery-point-' + number + '" class="btn btn-primary btn-sm" onclick="return false;" style="padding: 4px 35px">' + _('I CHOOSE') + '</a>' : '') + '</p>'
            + '</div>';
    }
    $('#delivery-point-list').html(html.join(''));
}

$("input[value^='" + carrier_id + ",']").change(
    function (e) {
        if ($(this).is(':checked')) {
            e.stopPropagation();
            $('#upela-delivery').parent('div').show();
            initializeMap({id: 'map-upela-selected', lat: first.latitude, lng: first.longitude, zoom: 11});
            var latlng = new google.maps.LatLng(
                parseFloat(first.latitude),
                parseFloat(first.longitude));
            first.number = 1;
            createMarker(latlng, first);
            google.maps.event.trigger(map, 'resize');
        }
    });


$("input[value^='" + carrier_id_parent + ",']").change(
    function (e) {
        if ($(this).is(':checked')) {
            e.stopPropagation();
            $('#upela-delivery').parent('div').show();
            initializeMap({id: 'map-upela-selected', lat: first.latitude, lng: first.longitude, zoom: 11});
            var latlng = new google.maps.LatLng(
                parseFloat(first.latitude),
                parseFloat(first.longitude));
            first.number = 1;
            createMarker(latlng, first);
            google.maps.event.trigger(map, 'resize');
        }
    });

$(document).ready(function () {

    if (typeof url !== 'undefined' && typeof unableCarrier !== 'undefined' && unableCarrier.length > 0) {
        for (i = 0; i < unableCarrier.length; i++) {
            removeCarrier(unableCarrier[i]);
        }
    }
    if (typeof url === 'undefined') {
        return;
    }
    $.ajax({
            url: url,
            type: 'GET',
            success: function (s) {
                console.log('request dropoff');
                if (s.rows.length == 0) {
                    $('#selected-delivery-point').text($('#tr_nodp').val());
                }
                else {
                    if (readCookie('dropoffLocation') === null) {
                        first = s.rows[0];
                        createCookie('dropoffLocation', JSON.stringify(s.rows[0]), 1);
                    }
                    else {
                        first = JSON.parse(readCookie('dropoffLocation'))
                    }
                    initializeMap({id: 'map-upela-selected', lat: first.latitude, lng: first.longitude, zoom: 12});
                    listDroppOff = s.rows;
                    setSelectedVal(first);
                    var latlng = new google.maps.LatLng(
                        parseFloat(first.latitude),
                        parseFloat(first.longitude));
                    first.number = 1;
                    createMarker(latlng, first);
                }
            },
            error: function (e) {
                console.log(e);
                $('#selected-delivery-point').text($('#tr_nodp').val());
            }
        }
    );
});
$('#choose-delivery').on('click', function () {
    $('#upelaModal').modal('show');
});
$('#upelaModal').on('shown.bs.modal', function () {
    initializeMap({id: 'map-upela', lat: first.latitude, lng: first.longitude, zoom: 12});
    setSelectedVal(first);
    setDropOffPoints(listDroppOff);
});


$('body').on('click', '.upela-marker-click', function () {
    var number = $(this).data('href');
    setSelectedVal(listDroppOff[number]);
    // enregistrement en bdd
    var data = listDroppOff[number];
    eraseCookie('dropoffLocation');
    createCookie('dropoffLocation', JSON.stringify(data), 1);
    $('#upelaModal').modal('hide');
    initializeMap({id: 'map-upela-selected', lat: data.latitude, lng: data.longitude, zoom: 11});
    var latlng = new google.maps.LatLng(
        parseFloat(data.latitude),
        parseFloat(data.longitude));
    data.number = 1;
    createMarker(latlng, data);
    google.maps.event.trigger(map, 'resize');
});

$('#checkout-delivery-step h1').on('click', function () {
    $('#map-upela-selected').html('Loading map ...');
    setTimeout(function () {
        initializeMap({id: 'map-upela-selected', lat: first.latitude, lng: first.longitude, zoom: 11});
        var latlng = new google.maps.LatLng(
            parseFloat(first.latitude),
            parseFloat(first.longitude));
        first.number = 1;
        createMarker(latlng, first);
        google.maps.event.trigger(first, 'resize');
    }, 500);
});


function sendCommandeToUpela($data) {
    var url = '/index.php?fc=module&module=upela&controller=ajax&option=directShiping';

    var data = $data;

    data.content = $('#ship_content').val();
    data.reason = $('#tr_reason').val();
    data.parcels[0].number = $('#upela_count').val();
    data.parcels[0].weight = $('#upela_weight').val();
    data.parcels[0].x = $('#upela_length').val();
    data.parcels[0].y = $('#upela_width').val();
    data.parcels[0].z = $('#upela_height').val();

    $('#upela-expedier').html($('#tr_progress').val());
    $('#upela-expedier').attr('onclick', '');

    $.ajax({
        url: url,
        type: 'POST',
        data: $data,
        success: function (s) {
            var result = JSON.parse(s);
            if (result.success === false) {
                $('#upela-error').html($('#tr_error1').val());
                console.log(result);
            } else {

                if(typeof imprimerLeBordereau === 'undefined')
                {
                    imprimerLeBordereau = 'Imprimer le bordereau';
                }
                var WayBilllink = '<tr><td class="up-sended-td">';
                WayBilllink += '<img  src="' + pdfImg + '" width="150px;">';
                WayBilllink = WayBilllink + '<br><a href="' + result.waybill.url + '" target="blank" class="btn btn-primary text-center part__button" style="background-color: #FF6600">'+imprimerLeBordereau+'</a></td></tr>';
                $('#table-body-upela').html(WayBilllink);
            }
        },
        error: function (e) {
            console.log(e);
        }

    });
}

function removeCarrier(carrier) {
    if (typeof carrier == "undefined") {
        console.log('unableto find carrier');
        return;
    }
    var carrierDiv = "input[value^='" + carrier + ",']";
    // for 1.6, 1.5
    var carrierDivParent = $(".delivery_option").has(carrierDiv);
    // for 1.7
    if (carrierDivParent.length === 0) {
        carrierDivParent = $(".delivery-option").has(carrierDiv);
    }
    if (carrierDivParent.length) {
        carrierDivParent.attr('style', 'display:none');
        carrierDivParent.find("input").attr("disabled", true);
    }
}

