<?php

//
// 店舗リスト
//
function fnSqlStoreSelectByArea($selectedArea)
{
    $sql = "SELECT STORE FROM TBLSTORE WHERE AREA = '$selectedArea' AND DEL = 1";
    return $sql;
}

//
// 店舗リスト
//
function fnSqlStoreList($flg, $param)
{
    switch ($flg) {
        case 0:
            $select = "SELECT COUNT(*)";
            $order = "";
            $limit = "";
            break;
        case 1:
            $select = "SELECT STORENO, STORE, AREA, NOTE";
            // 並び替えとデータ抽出数
            if ($param["orderBy"]) {
                $order = " ORDER BY " . $param["orderBy"] . " " . $param["orderTo"];
            }
            $limit = " LIMIT " . (($param["sPage"] - 1) * PAGE_MAX) . ", " . PAGE_MAX;
            break;
    }
    $from = " FROM TBLSTORE";
    $where = " WHERE DEL = 1";

    // 検索条件
    if ($param["sStore"]) {
        $where .= " AND STORE = '" . $param["sStore"] . "'";
    }
    if ($param["sArea"]) {
        $where .= " AND AREA  = '" . $param["sArea"] . "'";
    }
    if ($param["sNote"]) {
        $where .= " AND NOTE LIKE '%" . $param["sNote"] . "%'";
    }

    return $select . $from . $where . $order . $limit;
}

//
// 店舗情報
//
function fnSqlStoreEdit($storeNo)
{
    $select  = "SELECT STORE, AREA, NOTE";
    $from = " FROM TBLSTORE";
    $where = " WHERE DEL = 1";
    $where .= " AND STORENO = $storeNo";

    return $select . $from . $where;
}

//
// 店舗情報更新
//
function fnSqlStoreUpdate($param)
{
    $sql = "UPDATE TBLSTORE";
    $sql .= " SET STORE = '" . $param["store"] . "'";
    $sql .= ", AREA = '" . $param["area"] . "'";
    $sql .= ", NOTE = '" . $param["note"] . "'";
    $sql .= ",UPDT = CURRENT_TIMESTAMP";
    $sql .= " WHERE STORENO = " . $param["storeNo"];

    return $sql;
}

//
// 店舗情報登録
//
function fnSqlStoreInsert($param)
{
    $sql = "INSERT INTO TBLSTORE(";
    $sql .= "STORENO,STORE,AREA,NOTE,INSDT,UPDT,DEL";
    $sql .= ")VALUES(";
    $sql .= "'" . $param["storeNo"] . "','" . $param["store"] . "','"
        . $param["area"] . "','" . $param["note"] . "',"
        . "CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,1)";

    return $sql;
}

//
// 店舗情報削除
//
function fnSqlStoreDelete($storeNo)
{
    $sql = "UPDATE TBLSTORE";
    $sql .= " SET DEL = -1";
    $sql .= ",UPDT = CURRENT_TIMESTAMP";
    $sql .= " WHERE STORENO = '$storeNo'";

    return $sql;
}
