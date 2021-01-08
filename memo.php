<?php


function dbConnect(){
    $link = mysqli_connect('db','book_log','pass','book_log');
    if(!$link){
        echo 'データベースの接続に失敗しました' . PHP_EOL;
        echo 'Debugging error:' . mysqli_connect_error() . PHP_EOL;
    exit;
    }
    return $link;
}
$link = dbConnect();

function validate($memo){
    $error = [];
    if($memo['memo'] === ''){
        echo 'メモを入力して下さい' . PHP_EOL;
    }elseif(strlen($memo['memo']) > 2000){
        echo '2000文字以内で入力して下さい' . PHP_EOL;
    }
    if($memo['writer'] === ''){
        echo '名前を入力して下さい' . PHP_EOL;
    } elseif ($memo['writer'] > 255) {
        echo '255文字以内で入力して下さい' . PHP_EOL;
    }
    return $error;
}

function createMemos($link){

    $memo = [];
    echo 'メモを入力して下さい' . PHP_EOL;
    echo 'メモ:';
    $memo['memo'] = trim(fgets(STDIN));

    echo '名前:';
    $memo['writer'] = trim(fgets(STDIN));

    $validated = validate($memo);
    if(count($validated) > 0){
        foreach($validated as $error){
            echo $error . PHP_EOL;
        }
        return;
    }


$sql = <<< EOT
    INSERT INTO memos(
        memo,
        writer
    )
    VALUES(
        "{$memo['memo']}",
        "{$memo['writer']}"
    )
EOT;


    $result = mysqli_query($link,$sql);

    if($result){
        echo '登録が完了しました' . PHP_EOL;
    }else {
        echo '登録に失敗しました' . PHP_EOL;
        echo 'Debugging Error:' . mysqli_error($link) . PHP_EOL;
    }
}
function displayMemos($link){
    echo 'メモを表示します' . PHP_EOL;


    $sql = 'SELECT id,memo,writer,created_at FROM memos';
    $results = mysqli_query($link,$sql);

    while($memo = mysqli_fetch_assoc($results)){
        echo 'ID:' . $memo['id'] . PHP_EOL;
        echo 'メモ:' . $memo['memo'] . PHP_EOL;
        echo '記録者:' . $memo['writer'] . PHP_EOL;
        echo '記録日時:' . $memo['created_at'] . PHP_EOL;
    }
}


while(true){
    echo '1.メモを入力する' . PHP_EOL;
    echo '2.メモを表示する' . PHP_EOL;
    echo '3.メモを終了する' . PHP_EOL;
    echo '数字を入力して下さい:';
    $num = trim(fgets(STDIN));

    if($num === '1'){
       createMemos($link);
    } elseif($num === '2'){
        displayMemos($link);
    }elseif($num === '3'){
        mysqli_close($link);
        break;
    }
}
?>
