<?php
require_once('lib/geraxml.php');
require_once('lib/MobileDetect.php');
$detect = new Detection\MobileDetect;
?>

<!doctype html>
<html lang="pt-BR">

<head>
    <meta name="robots" content="noindex, nofollow" />
    <link rel="icon" href="img/rainy.png">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>APS - Mapa do alagamento</title>
    <meta name="description" content="APS" />
    <!--[if lt IE 9]>
    <script src="http://html5shivprintshiv.googlecode.com/svn/trunk/html5shiv-printshiv.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php if ($detect->isMobile()): ?>
        <div class="container">
            <div class="col-lg-12 col-md-12 page-header">
                <h1 class="title">APS</h1>
            </div>
        </div>
        <div class="container">
            <div class="panel panel-default">
                <div class="panel-heading">Informações obtidas via Google Maps API</div>
                <div class="panel-body">
                    <form action="lib/salvar.php" class="col-xs-12" method="post">
                        <h4 class="subtitle">Você está aqui</h4>
                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Latitude"
                                readonly="readonly">
                        </div>
                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Longitude"
                                readonly="readonly">
                        </div>
                        <div class="form-group">
                            <label for="endereco">Endereço</label>
                            <input type="text" class="form-control" name="endereco" id="endereco" placeholder="Endereço"
                                readonly="readonly">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary getLocation">
                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Área alagada
                            </button>
                            <p class="">Clique no botão acima para informar que você está em uma área de
                                alagamento.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="panel panel-default">
                <div class="panel-heading">Mapa do alagamento</div>
                <div class="panel-body">
                    <p class="help-block">Pontos de alagamento: <span id="pontos" class="badge"></span></p>
                    <div id="map" class="col-xs-12" style="height:450px"></div>
                </div>
            </div>
        </div>
        <div id="myModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <p id="msg"></p>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8rO9ZKW08QPqbmfijzY4v9ngVMEY2y-w&libraries=places"></script>
        <?php if (isset($_GET['null'])): ?>
            <script>
                $(window).on('load', function () {
                    modal.innerHTML = "Não conseguimos obter suas coordenadas, tente novamente.";
                    $("#myModal").modal("show");
                });
            </script>
        <?php endif; ?>

        <script>
            $(document).ready(function () {
                var modal = document.getElementById("msg");
                var latitude = document.getElementById("latitude");
                var longitude = document.getElementById("longitude");
                var endereco = document.getElementById("endereco");
                var pontos = document.getElementById("pontos");

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(d, showError);
                } else {
                    modal.innerHTML = "O seu navegador não suporta Geolocalização.";
                    $("#myModal").modal("show");
                }

                function d(i) {
                    var j = new google.maps.LatLng(i.coords.latitude, i.coords.longitude);
                    latitude.value = i.coords.latitude;
                    longitude.value = i.coords.longitude;

                    var marker = new google.maps.Marker({
                        position: j,
                        map: g
                    });

                    google.maps.event.addListener(marker, "click", function () {
                        e.setContent("Seu local");
                        e.open(g, marker);
                    });

                    h.geocode({
                        location: j
                    }, function (l, k) {
                        if (k === google.maps.GeocoderStatus.OK) {
                            if (l[1]) {
                                endereco.value = l[1].formatted_address;
                            } else {
                                window.alert("Nenhum resultado encontrado");
                            }
                        } else {
                            window.alert("Falha: " + k);
                        }
                    });
                }


                function showError(error) {
                    // Trate os erros de geolocalização aqui, se necessário.
                    console.error(error);
                }

                var g = new google.maps.Map(document.getElementById("map"), {
                    zoom: 10,
                    scrollwheel: false,
                    center: new google.maps.LatLng(-23.5483498, -46.3801577),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                var e = new google.maps.InfoWindow();
                var h = new google.maps.Geocoder;

                var f = new XMLHttpRequest();
                f.onreadystatechange = function () {
                    if (f.readyState == 4 && f.status == 200) {
                        a(f);
                    }
                };
                f.open("GET", "dados/dados.xml", true);
                f.send();

                function a(j) {
                    var r = j.responseXML;
                    var l = [];
                    var k = [];
                    var o = [];
                    var p = [];
                    var n = [];
                    var q = [];
                    var m = [];
                    var i = [];

                    for (var c = 0; c < r.getElementsByTagName("coordenadas").length; c++) {
                        l[c] = r.getElementsByTagName("coordenadas")[c];
                        k[c] = l[c].childNodes[0].nodeValue;
                        q[c] = k[c].split(":");
                        n[c] = q[c][0].split(",");
                        pontos.innerHTML = k.length;
                        o[c] = n[c][0];
                        p[c] = n[c][1];
                        m[c] = r.getElementsByTagName("endereco")[c];
                        i[c] = m[c].childNodes[0].nodeValue;

                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(parseFloat(o[c]), parseFloat(p[c])),
                            map: g,
                            icon: "img/rainy.png"
                        });

                        google.maps.event.addListener(marker, "click", function (s, t) {
                            e.setContent("Coordenadas: " + o[t] + ", " + p[t] + " <br> Endereço: " + i[t]);
                            e.open(g, s);
                        });
                    }
                }
            });
        </script>

    <?php else: ?>
        <div class="container">
            <div class="col-lg-12 col-md-12 page-header">
                <h1 class="text"><strong>Este sistema funciona apenas em dispositivo móvel.</strong></h1>
            </div>
        </div>
    <?php endif; ?>
</body>

</html>