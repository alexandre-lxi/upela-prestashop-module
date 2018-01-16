/**
 * Created by Upela 12/01/2018
 */
$.noConflict();

var map;
var markers = [];
var infoWindow;
var first = false;
var listDroppOff =false;


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
}
function setSelectedVal(dropoffLocation)
{
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
    closeInfoWindow = function() {
        infoWindow.close();
    };
    google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
    });
    google.maps.event.addListener(map, 'click', closeInfoWindow);
    markers.push(marker);
}
function setDropOffPoints(dropOfflist)
{
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

$('#delivery_option_' + carrier_id).change(
    function (e) {
        if ($(this).is(':checked') ) {
            e.stopPropagation();
            $('#upela-delivery').parent('div').show();
            initializeMap({id:'map-upela-selected',lat:first.latitude,lng:first.longitude,zoom:11});
            var latlng = new google.maps.LatLng(
                parseFloat(first.latitude),
                parseFloat(first.longitude));
                first.number = 1;
                createMarker(latlng, first);
            google.maps.event.trigger(map, 'resize');
        }
    });

$(document).ready(function() {

    if(typeof url === 'undefined' )
    {
      return;
    }
    $.ajax({
            url: url,
            type: 'GET',
            success: function (s) {
                console.log('request dropoff');
                if (s.rows.length == 0) {
                    $('#selected-delivery-point').text('Impossible de trouver un point relais');
                }
                else {

                    if( readCookie('dropoffLocation') === null)
                    {
                        first = s.rows[0];
                    }
                    else
                    {
                        first = JSON.parse(readCookie('dropoffLocation'))
                    }

                    initializeMap({id:'map-upela-selected',lat:first.latitude,lng:first.longitude,zoom:1});

                    listDroppOff = s.rows;
                    setSelectedVal(first);
                }
            },
            error: function (e) {
                console.log(e);
                $('#selected-delivery-point').text('Impossible de trouver un point relais');
            }
        }
    );
});
$('#choose-delivery').on('click',function(){$('#upelaModal').modal('show');});
$('#upelaModal').on('shown.bs.modal', function() {
    initializeMap({id:'map-upela',lat:first.latitude,lng:first.longitude,zoom:12});
    setSelectedVal(first);
    setDropOffPoints(listDroppOff);
});
$('body').on('click','.upela-marker-click',function(){
    var number = $(this).data('href');
    setSelectedVal(listDroppOff[number]);
    // enregistrement en bdd
    var data = listDroppOff[number];
    eraseCookie('dropoffLocation');
    createCookie('dropoffLocation', JSON.stringify(data), 1);
    $('#upelaModal').modal('hide');
    initializeMap({id:'map-upela-selected',lat:data.latitude,lng:data.longitude,zoom:11});
    var latlng = new google.maps.LatLng(
        parseFloat(data.latitude),
        parseFloat(data.longitude));
    first.number = 1;
    createMarker(latlng, data);
    google.maps.event.trigger(map, 'resize');
});

