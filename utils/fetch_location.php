<?php
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://rwanda.p.rapidapi.com/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "x-rapidapi-host: rwanda.p.rapidapi.com",
        "x-rapidapi-key: 5e1e59cfdbmsh4c76c96fd73cd9cp1a957ejsn44db321aafc9"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo json_encode(["error" => "cURL Error: " . $err]);
} else {
    echo $response;
}
?>
