<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTransParams = array(
    'INIT_MAP_TYPE' => $arParams['INIT_MAP_TYPE'],
    'INIT_MAP_LON' => $arResult['POSITION']['google_lon'],
    'INIT_MAP_LAT' => $arResult['POSITION']['google_lat'],
    'INIT_MAP_SCALE' => $arResult['POSITION']['google_scale'],
    'MAP_WIDTH' => $arParams['MAP_WIDTH'],
    'MAP_HEIGHT' => $arParams['MAP_HEIGHT'],
    'CONTROLS' => $arParams['CONTROLS'],
    'OPTIONS' => $arParams['OPTIONS'],
    'MAP_ID' => $arParams['MAP_ID'],
);

if ($arParams['DEV_MODE'] == 'Y')
{
    $arTransParams['DEV_MODE'] = 'Y';
    if ($arParams['WAIT_FOR_EVENT'])
        $arTransParams['WAIT_FOR_EVENT'] = $arParams['WAIT_FOR_EVENT'];
}
?>
<?//arshow($arResult);?>
<script>
function init() {
    if ($("#contacts-map").length === 0) return;
    var map;
    var address = new google.maps.LatLng(<?=$arResult["POSITION"]["PLACEMARKS"][0]["LAT"]?>, <?=$arResult["POSITION"]["PLACEMARKS"][0]["LON"]?>);
    var MY_MAPTYPE_ID = 'mystyle';
    var stylez = [
        {
            stylers: [
                { "gamma": 1 },
                { "hue": "#ede9e6" },
                { "saturation": -50 },
                { "lightness": 1 }
            ]
        },
        {
            featureType: "water",
            stylers: [
                {color: "#ede9e6"}
            ]
        },
        {
            featureType: "poi",
            stylers: [
                {color: "#ece8e5"}
            ]
        },
        {
            featureType: "road.highway",
            elementType: "geometry",
            stylers: [
                {color: "#bebebe"}
            ]
        },
        {
            featureType: "road.highway",
            elementType: "geometry.fill",
            stylers: [
                {color: "#ded6d0"}
            ]
        }
    ];

    var mapOptions = {
        zoom: 13,
        center: address,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
        },
        mapTypeId: MY_MAPTYPE_ID
    };

    map = new google.maps.Map(document.getElementById("contacts-map"),
        mapOptions);

    var styledMapOptions = {
        name: "Мой стиль"
    };

    map.mapTypes.set(MY_MAPTYPE_ID, new google.maps.StyledMapType(stylez, styledMapOptions));

    var marker = new google.maps.Marker({
        position: address,
        map: map,
        icon: '/images/marker.png'
    });


}


$(function(){
    init();
})

</script>

 <div id="contacts-map" class="contacts-map div"></div>
 