<?php

//
// 店舗担当者一覧画面
//
function subCover()
{
    $param = getCoverParam();

    if ($param["sDel"] == '') {
        $param["sDel"] = 1;
    }

    if (! $param["sPage"]) {
        $param["sPage"] = 1;
    }

    if (! $param["orderBy"]) {
        $param["orderBy"] = 'COVERNO';
        $param["orderTo"] = 'desc';
    }

    subMenu();
    subCoverView($param);
}

//
// 店舗担当者編集画面
//
function subCoverEdit()
{
    $param = getCoverParam();

    $param["coverNo"] = $_REQUEST['coverNo'];

    if ($param["coverNo"]) {
        $sql = fnSqlCoverEdit($param["coverNo"]);
        $res = mysqli_query($param["conn"], $sql);
        $row = mysqli_fetch_array($res);

        $param["store"] = htmlspecialchars($row[0]);
        $param["cover"] = htmlspecialchars($row[1]);
        $param["mobile"] = htmlspecialchars($row[2]);

        $param["purpose"] = '更新';
        $param["btnImage"] = 'btn_load.png';
    } else {
        $param["purpose"] = '登録';
        $param["btnImage"] = 'btn_enter.png';
    }

    subMenu();
    subCoverEditView($param);
}

//
// 店舗担当者編集完了処理
//
function subCoverEditComplete()
{
    $param = getCoverParam();

    $param["coverNo"] = mysqli_real_escape_string($param["conn"], $_REQUEST['coverNo']);
    $param["store"] = mysqli_real_escape_string($param["conn"], $_REQUEST['store']);
    $param["cover"] = mysqli_real_escape_string($param["conn"], $_REQUEST['cover']);
    $param["mobile"] = mysqli_real_escape_string($param["conn"], $_REQUEST['mobile']);

    if ($param["coverNo"]) {
        $sql = fnSqlCoverUpdate($param);
        var_dump($sql);
        $res = mysqli_query($param["conn"], $sql);
    } else {
        $param["coverNo"] = fnNextNo('COVER');
        $sql = fnSqlCoverInsert($param);
        var_dump($sql);
        $res = mysqli_query($param["conn"], $sql);
    }

    $_REQUEST['act'] = 'coverSearch';
    subCover();
}

//
// 店舗担当者削除処理
//
function subCoverDelete()
{
    $conn = fnDbConnect();

    $param["coverNo"] = $_REQUEST['coverNo'];

    $sql = fnSqlCoverDelete($param["coverNo"]);
    $res = mysqli_query($conn, $sql);

    $_REQUEST['act'] = 'coverSearch';
    subCover();
}

//
// 画面間引継ぎ情報
//
function getCoverParam()
{
    $param = array();

    // DB接続
    $param["conn"] = fnDbConnect();

    // 検索情報
    $param["sStore"] = htmlspecialchars($_REQUEST['sStore']);
    $param["sCover"] = htmlspecialchars($_REQUEST['sCover']);
    $param["sMobile"] = htmlspecialchars($_REQUEST['sMobile']);

    $param["orderBy"] = $_REQUEST['orderBy'];
    $param["orderTo"] = $_REQUEST['orderTo'];
    $param["sPage"] = $_REQUEST['sPage'];

    return $param;
}
