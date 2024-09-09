<?php
require('class/stock/logic.php');
require('class/stock/model.php');
require('class/stock/view.php');

function stock_control()
{
    switch ($_REQUEST['act']) {

            // 仕入管理
        case 'stock':
        case 'stockSearch':   // 検索
            subStock();
            break;

        case 'stockEdit':  //　編集
            subStockEdit();
            break;

        case 'stockEditComplete':  //登録
            subStockEditComplete();
            break;

        case 'stockDelete': //削除
            subStockDelete();
            break;

        case 'stockListDelete': //一括削除
            subStockListDelete();
            break;
    }
}
