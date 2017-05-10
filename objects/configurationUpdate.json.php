<?php

header('Content-Type: application/json');
if (empty($global['systemRootPath'])) {
    $global['systemRootPath'] = "../";
}
require_once $global['systemRootPath'] . 'objects/user.php';
if (!User::isAdmin()) {
    die('{"error":"' . __("Permission denied") . '"}');
}

require_once $global['systemRootPath'] . 'videos/configuration.php';
require_once $global['systemRootPath'] . 'objects/configuration.php';
require_once $global['systemRootPath'] . 'objects/functions.php';
$config = new Configuration();
$config->setContactEmail($_POST['contactEmail']);
$config->setLanguage($_POST['language']);
$config->setVideo_resolution($_POST['video_resolution']);
$config->setWebSiteTitle($_POST['webSiteTitle']);
$config->setAuthCanComment($_POST['authCanComment']);
$config->setAuthCanUploadVideos($_POST['authCanUploadVideos']);
$config->setAuthFacebook_enabled($_POST['authFacebook_enabled']);
$config->setAuthFacebook_id($_POST['authFacebook_id']);
$config->setAuthFacebook_key($_POST['authFacebook_key']);
$config->setAuthGoogle_enabled($_POST['authGoogle_enabled']);
$config->setAuthGoogle_id($_POST['authGoogle_id']);
$config->setAuthGoogle_key($_POST['authGoogle_key']);
$config->setFfprobeDuration($_POST['ffprobeDuration']);
$config->setFfmpegImage($_POST['ffmpegImage']);
$config->setFfmpegMp4($_POST['ffmpegMp4']);
$config->setFfmpegWebm($_POST['ffmpegWebm']);
$config->setFfmpegMp3($_POST['ffmpegMp3']);
$config->setFfmpegOgg($_POST['ffmpegOgg']);
$config->setYoutubedl($_POST['youtubeDl']);
$config->setYoutubeDlPath($_POST['youtubeDlPath']);
$config->setFfmpegPath($_POST['ffmpegPath']);
$config->setHead($_POST['head']);
$config->setAdsense($_POST['adsense']);


$imagePath = "videos/userPhoto/";

//Check write Access to Directory
if (!file_exists($global['systemRootPath'] . $imagePath)) {
    mkdir($global['systemRootPath'] . $imagePath, 0777, true);
}

if (!is_writable($global['systemRootPath'] . $imagePath)) {
    $response = Array(
        "status" => 'error',
        "message" => 'No write Access'
    );
    print json_encode($response);
    return;
}
$response = $responseSmall = array();
if (!empty($_POST['logoImgBase64'])) {
    $fileData = base64DataToImage($_POST['logoImgBase64']);
    $fileName = 'logo.png';
    $photoURL = $imagePath . $fileName;
    $bytes = file_put_contents($global['systemRootPath'] . $photoURL, $fileData);
    if ($bytes) {
        $response = array(
            "status" => 'success',
            "url" => $global['systemRootPath'] . $photoURL
        );
    } else {
        $response = array(
            "status" => 'error',
            "msg" => 'We could not save logo',
            "url" => $global['systemRootPath'] . $photoURL
        );
    }
    $config->setLogo($photoURL);
}
if (!empty($_POST['logoSmallImgBase64'])) {
    $fileData = base64DataToImage($_POST['logoSmallImgBase64']);
    $fileName = 'logoSmall.png';
    $photoURL = $imagePath . $fileName;
    $bytes = file_put_contents($global['systemRootPath'] . $photoURL, $fileData);
    if ($bytes) {
        $responseSmall = array(
            "status" => 'success',
            "url" => $global['systemRootPath'] . $photoURL
        );
    } else {
        $responseSmall = array(
            "status" => 'error',
            "msg" => 'We could not save small logo',
            "url" => $global['systemRootPath'] . $photoURL
        );
    }
    $config->setLogo_small($photoURL);
}
echo '{"status":"' . $config->save() . '", "respnseLogo": ' . json_encode($response) . ', "respnseLogoSmall": ' . json_encode($responseSmall) . '}';
