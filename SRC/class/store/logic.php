<?php

//
// 店舗一覧画面
//
function subStore()
{
    $param = getStoreParam();

    if ($param["sDel"] == '') {
        $param["sDel"] = 1;
    }

    if (! $param["sPage"]) {
        $param["sPage"] = 1;
    }

    if (! $param["orderBy"]) {
        //$param["orderBy"] = 'SEARCHDT';
        $param["orderTo"] = 'desc';
    }

    subMenu();
    subStoreView($param);
}

//
// 店舗編集画面
//
function subStoreEdit()
{
    $param = getStoreParam();

    $param["storeNo"] = $_REQUEST['storeNo'];

    if ($param["storeNo"]) {
        $sql = fnSqlStoreEdit($param["storeNo"]);
        $res = mysqli_query($param["conn"], $sql);
        $row = mysqli_fetch_array($res);

        $param["store"] = htmlspecialchars($row[0]);

        $param["purpose"] = '更新';
        $param["btnImage"] = 'btn_load.png';
    } else {
        $param["purpose"] = '登録';
        $param["btnImage"] = 'btn_enter.png';
    }

    subMenu();
    subStoreEditView($param);
}

//
// 店舗編集完了処理
//
function subStoreEditComplete()
{
    $param = getStoreParam();

    $param["storeNo"] = mysqli_real_escape_string($param["conn"], $_REQUEST['storeNo']);
    $param["store"] = mysqli_real_escape_string($param["conn"], $_REQUEST['store']);

    if ($param["storeNo"]) {
        $sql = fnSqlStoreUpdate($param);
        $res = mysqli_query($param["conn"], $sql);
    } else {
        $param["storeNo"] = fnNextNo('STORE');
        $sql = fnSqlStoreInsert($param);
        var_dump($sql);
        $res = mysqli_query($param["conn"], $sql);
    }

    $_REQUEST['act'] = 'storeSearch';
    subStore();
}

//
// 店舗削除処理
//
function subStoreDelete()
{
    $conn = fnDbConnect();

    $param["storeNo"] = $_REQUEST['storeNo'];

    $sql = fnSqlStoreDelete($param["storeNo"]);
    $res = mysqli_query($conn, $sql);

    $_REQUEST['act'] = 'storeSearch';
    subStore();
}

//
// 画面間引継ぎ情報
//
function getStoreParam()
{
    $param = array();

    // DB接続
    $param["conn"] = fnDbConnect();

    // 検索情報
    $param["sStore"] = htmlspecialchars($_REQUEST['sStore']);

    $param["orderTo"] = $_REQUEST['orderTo'];
    $param["sPage"] = $_REQUEST['sPage'];

    return $param;
}
