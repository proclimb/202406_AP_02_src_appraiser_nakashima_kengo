<?php

//
// 店舗担当者リスト
//
function fnSqlCoverList($flg, $param)
{
    switch ($flg) {
        case 0:
            $select = "SELECT COUNT(*)";
            $order = "";
            $limit = "";
            break;
        case 1:
            $select = "SELECT COVERNO, STORE, COVER, MOBILE";
            // 並び替えとデータ抽出数
            if ($param["orderBy"]) {
                $order = " ORDER BY " . $param["orderBy"] . " " . $param["orderTo"];
            }
            $limit = " LIMIT " . (($param["sPage"] - 1) * PAGE_MAX) . ", " . PAGE_MAX;
            break;
    }
    $from = " FROM TBLCOVER";
    $where = " WHERE DEL = 1";

    // 検索条件
    if ($param["sStore"]) {
        $where .= " AND STORE LIKE '%" . $param["sStore"] . "%'";
    }
    if ($param["sCover"]) {
        $where .= " AND COVER LIKE '%" . $param["sCover"] . "%'";
    }
    if ($param["sMobile"]) {
        $where .= " AND MOBILE LIKE '%" . $param["sMobile"] . "%'";
    }

    return $select . $from . $where . $order . $limit;
}

//
// 店舗情報
//
function fnSqlCoverEdit($coverNo)
{
    $select  = "SELECT STORE, COVER, MOBILE";
    $from = " FROM TBLCOVER";
    $where = " WHERE DEL = 1";
    $where .= " AND COVERNO = $coverNo";

    return $select . $from . $where;
}

//
// 店舗情報更新
//
function fnSqlCoverUpdate($param)
{
    $sql = "UPDATE TBLCOVER";
    $sql .= " SET STORE = '" . $param["store"] . "'";
    $sql .= " COVER = '" . $param["cover"] . "'";
    $sql .= " MOBILE = '" . $param["mobile"] . "'";
    $sql .= ",UPDT = CURRENT_TIMESTAMP";
    $sql .= " WHERE COVERNO = " . $param["coverNo"];

    return $sql;
}

//
// 店舗情報登録
//
function fnSqlCoverInsert($param)
{
    $sql = "INSERT INTO TBLCOVER(";
    $sql .= "COVERNO,STORE,COVER,MOBILE,INSDT,UPDT,DEL";
    $sql .= ")VALUES(";
    $sql .= "'" . $param["coverNo"] . "','" . $param["store"] . "','" . $param["cover"] . "','" . $param["mobile"] . "',"
        . "CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,1)";

    return $sql;
}

//
// 店舗情報削除
//
function fnSqlCoverDelete($coverNo)
{
    $sql = "UPDATE TBLCOVER";
    $sql .= " SET DEL = -1";
    $sql .= ",UPDT = CURRENT_TIMESTAMP";
    $sql .= " WHERE COVERNO = '$coverNo'";

    return $sql;
}
