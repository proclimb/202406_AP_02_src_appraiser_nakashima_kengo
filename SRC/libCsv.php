<?php
function generateCsv($csvno, $csvdate)
{

    // DB接続
    $conn = fnDbConnect();

    // 受け取った値の処理　→　配列に変換
    $stockNos = explode(",", $csvdate);

    // CSV出力のSQL文実行
    //array_fill()関数で業者名の取得数分「？」で埋めた配列をimplode()関数で文字列に変換する
    $placeholders = implode(',', array_fill(0, $csvno, '?'));
    $sql = "SELECT * FROM TBLSTOCK WHERE STOCKNO IN ($placeholders)";

    // プリペアドステートメントを使用してSQLを実行
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, str_repeat('s', $csvno), ...$stockNos);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    //出力情報の設定
    $today = date("Ymd");
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=" . " users_" . $today . ".csv");
    header("Content-Transfer-Encoding: binary");

    // 出力バッファを開く
    $output = fopen('php://output', 'w');

    // ヘッダーを書き込む
    fputcsv($output, ['仕入番号', '担当者', 'ランク', '物件名', '物件名(よみ)', '部屋', '面積', '最寄り駅', '距離', '業者名', '店舗名', '担当者名', '内見', '机上金額', '売主希望金額', '備考', '仕入経緯', '登録日時', '更新日時', '除外/削除']); // 適切なカラム名に変更

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row); // 行データをCSVとして書き込む
    }

    // リソースを閉じる
    fclose($output);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    exit(); // スクリプトの実行を終了

}
