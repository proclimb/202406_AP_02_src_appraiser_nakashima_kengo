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
                    <th>店舗名</th>
                    <td colspan="6"><input type="text" name="sStore" value="<?php print $param["sStore"] ?>" size="60" /></td>
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
                </tr>
                <?php
                $sql  = fnSqlStoreList(1, $param);
                $res  = mysqli_query($param["conn"], $sql);
                $i = 0;
                while ($row = mysqli_fetch_array($res)) {
                    $storeNo   = htmlspecialchars($row[0]);
                    $store     = htmlspecialchars($row[1]);
                ?>
                    <tr>
                        <td class="list_td<?php print $i ?>"><a href="javascript:form.act.value='storeEdit';form.storeNo.value=<?php print $storeNo; ?>;form.submit();"><?php print $store; ?></a></td>
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
        <input type="hidden" name="storeNo" value="<?php print $param["storeNo"] ?>" />

        <table border="0" cellpadding="5" cellspacing="1">
            <tr>
                <th>店舗名<span class="red">（必須）</span></th>
                <td><input type="text" name="store" value="<?php print $param["store"] ?>" /></td>
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