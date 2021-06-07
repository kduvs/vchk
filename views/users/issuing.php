<?php

use yii\helpers\Url;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
//use app\assets\MyBadInstascanAsset;
use yii\web\View;
use mdq\instascan\QrReader;

$this->title = 'Выдача';

$this->params['breadcrumbs'][] = $this->title;



// $this->registerJs(
//     "let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
//     scanner.addListener('scan', function (content) {
//         console.log(content);
//     });
//     Instascan.Camera.getCameras().then(function (cameras) {
//         if (cameras.length > 0) {
//             scanner.start(cameras[0]);
//         } else {
//         console.error('No cameras found.');
//         }
//     }).catch(function (e) {
//         console.error(e);
//     });",
//     View::POS_BEGIN,
// );

    //'@web/js/main.js',
    //['depends' => [\yii\web\JqueryAsset::class]]
// $this->registerJsFile(
//     '/vchk\vendor/npm-asset/mybadinstascan/instascan-script.js',
//     ['depends' => [InstascanAsset::class]]
// );
?>

<?= QrReader::widget([
    'videoOptions' => ['class' => 'qr-reader-preview', 'style' => 'width: 300px; height: 188px; '],
    // When buttonLabel is "false", render camera immediately.
    'buttonLabel' => 'Scan',
    'buttonOptions' => ['class' => 'btn btn-default'],
    'options' => ['class' => 'qr-reader'],

    'successCallback' => 'function(data){console.log(data);}',
    'noCameraFoundCallback' => "function(cameras){console.error('No cameras found.');}",
    'initErrorCallback' => 'function (e) {console.error(e);}',

    // Whether to scan continuously for QR codes. If false, use scanner.scan() to manually scan.
    // If true, the scanner emits the "scan" event when a QR code is scanned. Default true.
    'continuous' => true,

    // Whether to horizontally mirror the video preview. This is helpful when trying to
    // scan a QR code with a user-facing camera. Default true.
    'mirror' => true,

    // Whether to include the scanned image data as part of the scan result. See the "scan" event
    // for image format details. Default false.
    'captureImage' => false,

    // Only applies to continuous mode. Whether to actively scan when the tab is not active.
    // When false, this reduces CPU usage when the tab is not active. Default true.
    'backgroundScan' => true,

    // Only applies to continuous mode. The period, in milliseconds, before the same QR code
    // will be recognized in succession. Default 5000 (5 seconds).
    'refractoryPeriod' => 5000,

    // Only applies to continuous mode. The period, in rendered frames, between scans. A lower scan period
    // increases CPU usage but makes scan response faster. Default 1 (i.e. analyze every frame).
    'scanPeriod' => 10,

    'debug' => YII_DEBUG,
]); ?>