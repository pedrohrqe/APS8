<?php

$coordenadas = file('dados/dados.txt');

$dom = new DOMDocument('1.0', 'UTF-8');

$data = $dom->createElement('data');

$dom->appendChild($data);

foreach ($coordenadas as $coordenada) {

    $dados = explode(":", $coordenada);

    $local = $dom->createElement('local');

    $cords = $dom->createElement('coordenadas', $dados[0]);

    $endereco = $dom->createElement('endereco', $dados[1]);

    $local->appendChild($cords);

    $local->appendChild($endereco);

    $data->appendChild($local);

}

file_put_contents('dados/dados.xml', $dom->saveXML());