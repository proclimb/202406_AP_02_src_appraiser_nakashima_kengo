<?php
require('class/cover/logic.php');
require('class/cover/model.php');
require('class/cover/view.php');

function cover_control()
{
    switch ($_REQUEST['act']) {

            // 店舗担当者管理
        case 'cover':
        case 'coverSearch':
            subCover();
            break;

        case 'coverEdit':
            subCoverEdit();
            break;

        case 'coverEditComplete':
            subCoverEditComplete();
            break;

        case 'coverDelete':
            subCoverDelete();
            break;
    }
}
