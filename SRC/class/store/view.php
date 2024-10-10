<?php
//
//店舗管理一覧画面
//
function subStoreView($param)
{
?>
    <script type="text/javascript" src="./js/store.js"></script>
    <h1>店舗管理一覧</h1>

    <form name="form" id="form" action="index.php" method="post">
        <input type="hidden" name="act" value="storeSearch" />
        <input type="hidden" name="orderBy" value="<?php print $param["orderBy"] ?>" />
        <input type="hidden" name="orderTo" value="<?php print $param["orderTo"] ?>" />
        <input type="hidden" name="sPage" value="<?php print $param["sPage"] ?>" />
        <input type="hidden" name="storeNo" />

        <a href="javascript:form.act.value='storeEdit';form.submit();"><img src="./images/btn_enter.png"></a>

        <div class="search">
            <table border="0" cellpadding="2" cellSpacing="0">
                <tr>
                    <th>エリア</th>
                    <td>
                        <select name="sArea" value="<?php print $param["sArea"] ?>" onchange="form.act.value='storeSearch';form.submit();">
                            <option value="">-----</option>
                            <?php
                            $areaList = ['東京', '埼玉', '群馬', '栃木', '茨城', '千葉', '神奈川'];
                            foreach ($areaList as $area):
                            ?>
                                <option value="<?php echo $area; ?>" <?php echo ($param["sArea"] === $area) ? 'selected' : ''; ?>>
                                    <?php echo $area; ?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>店舗名</th>
                    <td>
                        <select name="sStore" value="<?php print $param["sStore"] ?>">
                            <option value="">-----</option>
                            <?php
                            $selectedArea = $param["sArea"];
                            $sql = fnSqlStoreSelectByArea($selectedArea);
                            $res = mysqli_query($param["conn"], $sql);
                            if (isset($param["sArea"])) {
                                while ($row = mysqli_fetch_array($res)) {
                                    $param["sStoreName"] = htmlspecialchars($row[0]);
                            ?>
                                    <option value="<?php print $param["sAreaName"]; ?>"
                                        <?php if ($param["sStoreName"] == $param["sStore"]) {
                                            print ' selected="selected"';
                                        } ?>><?php print $param["sStoreName"]; ?></option>
                            <?php
                                }
                            }
                            ?>


                        </select>
                    </td>
                </tr>
                <tr>
                    <th>備考</th>
                    <td colspan="6"><input type="text" name="sNote" value="<?php print $param["sNote"] ?>" size="30" /></td>
                </tr>
            </table>
        </div>

        <input type="image" src="./images/btn_search.png" onclick="form.act.value='storeSearch';form.sPage.value=1;form.submit();" />

        <hr />

        <?php
        if ($_REQUEST['act'] == 'store') {
            return;
        }

        $sql = fnSqlStoreList(0, $param);
        $res = mysqli_query($param["conn"], $sql);
        $row = mysqli_fetch_array($res);

        $count = $row[0];

        $sPage = fnPage($count, $param["sPage"], 'storeSearch');
        ?>

        <div class="list">
            <table border="0" cellpadding="5" cellspacing="1">
                <tr>
                    <th class="list_head">店舗名<?php fnOrder('STORE', 'storeSearch') ?></th>
                    <th class="list_head">店舗エリア<?php fnOrder('AREA', 'storeSearch') ?></th>
                    <th class="list_head">備考</th>
                </tr>
                <?php
                $sql  = fnSqlStoreList(1, $param);
                $res  = mysqli_query($param["conn"], $sql);
                $i = 0;
                while ($row = mysqli_fetch_array($res)) {
                    $storeNo   = htmlspecialchars($row[0]);
                    $store     = htmlspecialchars($row[1]);
                    $area      = htmlspecialchars($row[2]);
                    $note      = htmlspecialchars($row[3]);
                ?>
                    <tr>
                        <td class="list_td<?php print $i ?>"><a href="javascript:form.act.value='storeEdit';form.storeNo.value=<?php print $storeNo; ?>;form.submit();"><?php print $store; ?></a></td>
                        <td class="list_td<?php print $i ?>"><?php print $area; ?></td>
                        <td class="list_td<?php print $i ?>"><?php print $note; ?></td>
                    </tr>
                <?php
                    $i = ($i + 1) % 2;
                }
                ?>
            </table>
        </div>

    </form>
<?php
}



//
//店舗編集画面
//
function subStoreEditView($param)
{

?>
    <script type="text/javascript" src="./js/store.js"></script>

    <h1>店舗<?php print $param["purpose"] ?></h1>

    <form name="form" id="form" action="index.php" method="post">
        <input type="hidden" name="act" />
        <input type="hidden" name="sStore" value="<?php print $param["sStore"] ?>" />
        <input type="hidden" name="sArea" value="<?php print $param["sArea"] ?>" />
        <input type="hidden" name="sNote" value="<?php print $param["sNote"] ?>" />
        <input type="hidden" name="storeNo" value="<?php print $param["storeNo"] ?>" />

        <table border="0" cellpadding="5" cellspacing="1">
            <tr>
                <th><span class="red">● </span>店舗名</th>
                <td><input type="text" name="store" value="<?php print $param["store"] ?>" /></td>
            </tr>
            <tr>
                <th><span class="red">● </span>エリア</th>
                <td>
                    <select name="area">
                        <option value="">-----</option>
                        <?php
                        $areaList = ['東京', '埼玉', '群馬', '栃木', '茨城', '千葉', '神奈川', 'その他'];
                        foreach ($areaList as $area):
                            $selected = ($param["area"] === $area) ? 'selected' : '';
                        ?>
                            <option value="<?php print $area; ?>" <?php print $selected; ?>>
                                <?php print htmlspecialchars($area); ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>備考</th>
                <td><textarea name="note" cols="50" rows="10" value="<?php print $param["note"] ?>"><?php print $param["note"]; ?></textarea></td>
            </tr>

        </table>

        <a href="javascript:fnStoreEditCheck();"><img src="./images/<?php print $param["btnImage"] ?>" /></a>
        <a href="javascript:form.act.value='storeSearch';form.submit();"><img src="./images/btn_return.png" /></a>
        <?php
        if ($param["storeNo"]) {
        ?>
            <a href="javascript:fnStoreDeleteCheck(<?php print $param["storeNo"] ?>);"><img src="./images/btn_del.png" /></a>
        <?php
        }
        ?>

    </form>

<?php
}
?>