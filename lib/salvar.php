<?php

if ($_POST['latitude'] != null && $_POST['longitude'] != null && $_POST['endereco'] != null) {

    $latitude = htmlspecialchars($_POST['latitude']);

    $longitude = htmlspecialchars($_POST['longitude']);

    $endereco = htmlspecialchars($_POST['endereco']);

    $caminhoArquivo = "../dados/dados.txt";



    $f = fopen($caminhoArquivo, "a+");

    if ($f) {

        $linha = $latitude . ", " . $longitude . " : " . $endereco . "\n";

        if (fwrite($f, $linha)) {

            fclose($f);

            echo "<script>window.location='../index.php';</script>";

        } else {

            fclose($f);

            echo "<script>window.location='../index.php?erro=1';</script>";

        }

    } else {

        echo "<script>window.location='../index.php?erro=2';</script>";

    }

} else {

    echo "<script>window.location='../index.php?null=true';</script>";

}

?>