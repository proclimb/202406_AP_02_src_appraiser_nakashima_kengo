<?php
//
//仕入上位5位（金額順）
//
function fnSqlStockAmountRank()
{
    $sql = "SELECT COVER, SUM(DESKPRICE) AS AMOUNT, COUNT(*) AS COUNT
    FROM TBLSTOCK
    WHERE DEL = 1 GROUP BY COVER
    ORDER BY AMOUNT DESC
    LIMIT 5";
    return $sql;
}

function fnSqlStockNumberRank()
{
    $sql = "SELECT COVER, SUM(DESKPRICE) AS AMOUNT, COUNT(*) AS COUNT
    FROM TBLSTOCK
    WHERE DEL = 1 GROUP BY COVER
    ORDER BY COUNT DESC
    LIMIT 5";
    return $sql;
}

function fnSqlSellAmountRank()
{
    $sql = "SELECT SELLCHARGE, SUM(SELLPRICE) AS SELLPRICEAMOUNT, COUNT(*) AS COUNT
    FROM TBLARTICLE
    WHERE DEL = 1 AND CONSTFLG4 = 1 GROUP BY SELLCHARGE
    ORDER BY SELLPRICEAMOUNT DESC
    LIMIT 5";
    return $sql;
}

function fnSqlSellAmountNumber()
{
    $sql = "SELECT SELLCHARGE, SUM(SELLPRICE) AS SELLPRICEAMOUNT, COUNT(*) AS COUNT
    FROM TBLARTICLE
    WHERE DEL = 1 AND CONSTFLG4 = 1 GROUP BY SELLCHARGE
    ORDER BY COUNT DESC
    LIMIT 5";
    return $sql;
}

function fnSqlTodaysGuide()
{
    $sql = "SELECT SELLCHARGE, GUIDESTARTDT, GUIDEENDDT, ARTICLE
    FROM TBLARTICLE A, TBLGUIDE B
    WHERE B.DEL = 1 AND A.ARTICLENO = B.ARTICLENO";
    return $sql;
}
