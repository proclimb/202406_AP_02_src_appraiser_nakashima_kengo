<?php
require('class/store/logic.php');
require('class/store/model.php');
require('class/store/view.php');

function store_control()
{
    switch ($_REQUEST['act']) {

            // 店舗管理
        case 'store':
        case 'storeSearch':
            subStore();
            break;

        case 'storeEdit':
            subStoreEdit();
            break;

        case 'storeEditComplete':
            subStoreEditComplete();
            break;

        case 'storeDelete':
            subStoreDelete();
            break;
    }
}
