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
        $param["orderBy"] = 'STORENO';
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
        $param["area"] = htmlspecialchars($row[1]);
        $param["note"] = htmlspecialchars($row[2]);

        $param["purpose"] = '更新';
        $param["btnImage"] = 'btn_load.png';
    } else {
        $param["purpose"] = '登録';
        $param["btnImage"] = 'btn_enter.png';
    }

    if ($_REQUEST['area']) {

        $param["store"]        = htmlspecialchars($_REQUEST['store']);
        $param["area"]          = htmlspecialchars($_REQUEST['area']);
        $param["note"]       = htmlspecialchars($_REQUEST['note']);
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

    // 登録・更新画面から受け取った値を$param配列に格納し、SQLインジェクション対策としてエスケープ処理を行う
    $param["storeNo"] = mysqli_real_escape_string($param["conn"], $_REQUEST['storeNo']);
    $param["store"] = mysqli_real_escape_string($param["conn"], $_REQUEST['store']);
    $param["area"] = mysqli_real_escape_string($param["conn"], $_REQUEST['area']);
    $param["note"] = mysqli_real_escape_string($param["conn"], $_REQUEST['note']);

    if ($param["storeNo"]) {
        $sql = fnSqlStoreUpdate($param);
        var_dump($sql);
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
    $param["sArea"] = htmlspecialchars($_REQUEST['sArea']);
    $param["sNote"] = htmlspecialchars($_REQUEST['sNote']);

    $param["orderBy"] = $_REQUEST['orderBy'];
    $param["orderTo"] = $_REQUEST['orderTo'];
    $param["sPage"] = $_REQUEST['sPage'];

    return $param;
}
