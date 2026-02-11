<?php
ob_start();

$API_KEY = 'توکن_جدیدتو_اینجا_بذار';
define('API_KEY',$API_KEY);

function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res);
}

function sendMessage($chat_id,$text){
    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>$text
    ]);
}

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;

$chat_id = $message->chat->id;
$from_id = $message->from->id;
$text = $message->text;
$name = $message->from->first_name;

// چک عضویت کانال
$channel = "@ferrybeats";
$check = json_decode(file_get_contents("https://api.telegram.org/bot$API_KEY/getChatMember?chat_id=$channel&user_id=$from_id"));
$status = $check->result->status;

if($status != 'member' && $status != 'creator' && $status != 'administrator'){
    
    sendMessage($chat_id,"عضو شو اول 👇\n$channel");

    bot('deleteMessage',[
        'chat_id'=>$chat_id,
        'message_id'=>$message->message_id
    ]);
}

?>
