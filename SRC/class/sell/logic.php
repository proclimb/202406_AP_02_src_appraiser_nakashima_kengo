<?php

//
// 売主物件画面
//
function subSell()
{
    $param = getSellParam();

    if ($param["sDel"] == '') {
        $param["sDel"] = 1;
    }

    if (! $param["sPage"]) {
        $param["sPage"] = 1;
    }

    if (! $param["orderBy"]) {
        $param["orderBy"] = 'SEARCHDT';
        $param["orderTo"] = 'desc';
    }

    subMenu();
    subSellView($param);
}

//
//売主一括登録画面
//
function subSellCsv()
{
    $param = getSellParam();
    if ($param["sDel"] == '') {
        $param["sDel"] = 1;
    }

    if (!$param["sPage"]) {
        $param["sPage"] = 1;
    }

    if (!$param["orderBy"]) {
        $param["orderBy"] = 'SEARCHDT';
        $param["orderTo"] = 'desc';
    }

    subMenu();
    subSellCsvView($param);
}

//
//売主一括登録
//
function displaySellCsvView()
{
    $param = getSellParam();
    subMenu();
    subSellCsvView($param);
}

function subSellCsvEdit()
{
    $csvFile = $_FILES['userFile'];

    // ファイルアップロードのエラーチェック
    if ($csvFile['error'] !== UPLOAD_ERR_OK) {
        displaySellCsvView();
        switch ($csvFile['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                echo "ファイルサイズが大きすぎます。";
                break;
            case UPLOAD_ERR_NO_FILE:
                echo "ファイルが選択されていません。";
                break;
            default:
                echo "不明なエラーが発生しました。";
                break;
        }
        return;
    }

    // ファイルサイズ確認
    if ($csvFile['size'] === 0) {
        displaySellCsvView();
        echo "ファイルが空です。";
        return;
    }

    // ファイル形式チェック
    $extension = strtolower(pathinfo($csvFile['name'], PATHINFO_EXTENSION));

    if ($extension != 'csv') {
        displaySellCsvView();
        echo "CSVファイルをアップロードしてください。";
        return;
    }

    $param["conn"] = fnDbConnect();
    // トランザクションを開始
    mysqli_begin_transaction($param["conn"]);

    // ファイルオープン
    $fp = fopen($_FILES['userFile']['tmp_name'], "r");

    if ($fp !== false) {
        // ファイルの内容を配列に読み込む
        while (($data[] = fgetcsv($fp)) !== false) : endwhile;
        //配列の最初の要素（ヘッダー行）を削除
        array_shift($data);
        //配列の最後の要素（空行や不要な行）を削除
        array_pop($data);
        //print_r($data);
    }

    //sellNoの最大値取得
    $param["sellNo"] = fnNextNo('SELL') - 1;
    $count = 0;

    foreach ($data as $row) {
        $param["sellNo"]++;
        $param["searchDT"] = htmlspecialchars($row[1]);
        $param["article"]  = htmlspecialchars($row[2]);
        $param["address"]  = htmlspecialchars($row[3]);
        $param["station"]  = htmlspecialchars($row[4]);
        $param["foot"]     = htmlspecialchars($row[5]);
        $param["years"]    = htmlspecialchars($row[6]);
        $param["floor"]    = htmlspecialchars($row[7]);
        $param["area"]     = htmlspecialchars($row[8]);
        $param["seller"]   = htmlspecialchars($row[9]);
        $param["price"]    = htmlspecialchars($row[10]);
        $param["note"]     = htmlspecialchars($row[11]);
        $count++;
        //入力チェック
        fnCsvCheck($param, $count);
        $sql = fnSqlSellCsvInsert($param);
        $res = mysqli_query($param["conn"], $sql);
    }
    //登録処理
    mysqli_commit($param["conn"]);
    fclose($fp);
    $param = getSellParam();

    if ($param["sDel"] == '') {
        $param["sDel"] = 1;
    }
    subMenu();
    subSellView($param);

    // 登録確認メッセージ
    $message = $count . "件の登録が完了しました。";
    $alert   = "<script type='text/javascript'>alert('" . $message . "');</script>";
    echo $alert;
}

//
//CSVチェック
//
function fnCsvCheck($param, $count)
{
    $param["conn"] = fnDbConnect();

    //日付エラーメッセージ
    if (!$param["searchDT"]) {
        mysqli_rollback($param["conn"]);
        $message = $count . "件目の日付が未入力です。";
        $alert   = "<script type='text/javascript'>alert('" . $message . "');</script>";
        echo $alert;
        subSellCsv();
        exit();
    }

    //物件名エラーメッセージ
    if (!$param["article"]) {
        mysqli_rollback($param["conn"]);
        $message = $count . "件目の物件名が未入力です。";
        $alert   = "<script type='text/javascript'>alert('" . $message . "');</script>";
        echo $alert;
        subSellCsv();
        exit();
    }

    //住所エラーメッセージ
    if (!$param["address"]) {
        mysqli_rollback($param["conn"]);
        $message = $count . "件目の住所が未入力です。";
        $alert   = "<script type='text/javascript'>alert('" . $message . "');</script>";
        echo $alert;
        subSellCsv();
        exit();
    }

    //徒歩エラーメッセージ
    if (!$param["foot"]) {
        mysqli_rollback($param["conn"]);
        $message = $count . "件目の徒歩が未入力です。";
        $alert   = "<script type='text/javascript'>alert('" . $message . "');</script>";
        echo $alert;
        subSellCsv();
        exit();
    }

    //築年エラーメッセージ
    if (!$param["years"]) {
        mysqli_rollback($param["conn"]);
        $message = $count . "件目の築年が未入力です。";
        $alert   = "<script type='text/javascript'>alert('" . $message . "');</script>";
        echo $alert;
        subSellCsv();
        exit();
    }

    //階数エラーメッセージ
    if (!$param["floor"]) {
        mysqli_rollback($param["conn"]);
        $message = $count . "件目の階数が未入力です。";
        $alert   = "<script type='text/javascript'>alert('" . $message . "');</script>";
        echo $alert;
        subSellCsv();
        exit();
    }

    //専有面積エラーメッセージ
    if (!$param["area"]) {
        mysqli_rollback($param["conn"]);
        $message = $count . "件目の専有面積が未入力です。";
        $alert   = "<script type='text/javascript'>alert('" . $message . "');</script>";
        echo $alert;
        subSellCsv();
        exit();
    }

    //売主エラーメッセージ
    if (!$param["seller"]) {
        mysqli_rollback($param["conn"]);
        $message = $count . "件目の売主が未入力です。";
        $alert   = "<script type='text/javascript'>alert('" . $message . "');</script>";
        echo $alert;
        subSellCsv();
        exit();
    }

    //価格エラーメッセージ
    if (!$param["price"]) {
        mysqli_rollback($param["conn"]);
        $message = $count . "件目の価格が未入力です。";
        $alert   = "<script type='text/javascript'>alert('" . $message . "');</script>";
        echo $alert;
        subSellCsv();
        exit();
    }
}

//
// 売主物件編集画面
//
function subSellEdit()
{
    $param = getSellParam();

    $param["sellNo"] = $_REQUEST['sellNo'];

    if ($param["sellNo"]) {
        $sql = fnSqlSellEdit($param["sellNo"]);
        $res = mysqli_query($param["conn"], $sql);
        $row = mysqli_fetch_array($res);

        $param["searchDT"] = htmlspecialchars($row[0]);
        $param["article"] = htmlspecialchars($row[1]);
        $param["address"] = htmlspecialchars($row[2]);
        $param["station"] = htmlspecialchars($row[3]);
        $param["foot"] = htmlspecialchars($row[4]);
        $param["years"] = htmlspecialchars($row[5]);
        $param["floor"] = htmlspecialchars($row[6]);
        $param["area"] = htmlspecialchars($row[7]);
        $param["seller"] = htmlspecialchars($row[8]);
        $param["price"] = htmlspecialchars($row[9]);
        $param["note"] = htmlspecialchars($row[10]);

        $param["purpose"] = '更新';
        $param["btnImage"] = 'btn_load.png';
    } else {
        $param["purpose"] = '登録';
        $param["btnImage"] = 'btn_enter.png';
    }

    subMenu();
    subSellEditView($param);
}

//
// 売主物件編集完了処理
//
function subSellEditComplete()
{
    $param = getSellParam();

    $param["sellNo"] = mysqli_real_escape_string($param["conn"], $_REQUEST['sellNo']);
    $param["searchDT"] = mysqli_real_escape_string($param["conn"], $_REQUEST['searchDT']);
    $param["article"] = mysqli_real_escape_string($param["conn"], $_REQUEST['article']);
    $param["address"] = mysqli_real_escape_string($param["conn"], $_REQUEST['address']);
    $param["station"] = mysqli_real_escape_string($param["conn"], $_REQUEST['station']);
    $param["foot"] = mysqli_real_escape_string($param["conn"], $_REQUEST['foot']);
    $param["years"] = mysqli_real_escape_string($param["conn"], $_REQUEST['years']);
    $param["floor"] = mysqli_real_escape_string($param["conn"], $_REQUEST['floor']);
    $param["area"] = mysqli_real_escape_string($param["conn"], $_REQUEST['area']);
    $param["seller"] = mysqli_real_escape_string($param["conn"], $_REQUEST['seller']);
    $param["price"] = mysqli_real_escape_string($param["conn"], $_REQUEST['price']);
    $param["note"] = mysqli_real_escape_string($param["conn"], $_REQUEST['note']);

    if ($param["sellNo"]) {
        $sql = fnSqlSellUpdate($param);
        $res = mysqli_query($param["conn"], $sql);
    } else {
        $param["sellNo"] = fnNextNo('SELL');
        $sql = fnSqlSellInsert($param);
        var_dump($sql);
        $res = mysqli_query($param["conn"], $sql);
    }

    $_REQUEST['act'] = 'sellSearch';
    subSell();
}

//
// 売主物件削除処理
//
function subSellDelete()
{
    $conn = fnDbConnect();

    $param["sellNo"] = $_REQUEST['sellNo'];

    $sql = fnSqlSellDelete($param["sellNo"]);
    $res = mysqli_query($conn, $sql);

    $_REQUEST['act'] = 'sellSearch';
    subSell();
}

//
// 画面間引継ぎ情報
//
function getSellParam()
{
    $param = array();

    // DB接続
    $param["conn"] = fnDbConnect();

    // 検索情報
    $param["sSearchFrom"] = htmlspecialchars($_REQUEST['sSearchFrom']);
    $param["sSearchTo"] = htmlspecialchars($_REQUEST['sSearchTo']);
    $param["sArticle"] = htmlspecialchars($_REQUEST['sArticle']);
    $param["sAddress"] = htmlspecialchars($_REQUEST['sAddress']);
    $param["sStation"] = htmlspecialchars($_REQUEST['sStation']);
    $param["sFoot"] = htmlspecialchars($_REQUEST['sFoot']);
    $param["sFootC"] = htmlspecialchars($_REQUEST['sFootC']);
    $param["sAreaFrom"] = htmlspecialchars($_REQUEST['sAreaFrom']);
    $param["sAreaTo"] = htmlspecialchars($_REQUEST['sAreaTo']);
    $param["sYearsFrom"] = htmlspecialchars($_REQUEST['sYearsFrom']);
    $param["sYearsTo"] = htmlspecialchars($_REQUEST['sYearsTo']);
    $param["sPriceFrom"] = htmlspecialchars($_REQUEST['sPriceFrom']);
    $param["sPriceTo"] = htmlspecialchars($_REQUEST['sPriceTo']);
    $param["sSeller"] = htmlspecialchars($_REQUEST['sSeller']);

    $param["orderBy"] = $_REQUEST['orderBy'];
    $param["orderTo"] = $_REQUEST['orderTo'];
    $param["sPage"] = $_REQUEST['sPage'];

    return $param;
}
