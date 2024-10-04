<?php
//
//店舗担当者管理一覧画面
//
function subCoverView($param)
{
?>
    <script type="text/javascript" src="./js/cover.js"></script>
    <h1>店舗担当者管理一覧</h1>

    <form name="form" id="form" action="index.php" method="post">
        <input type="hidden" name="act" value="coverSearch" />
        <input type="hidden" name="orderBy" value="<?php print $param["orderBy"] ?>" />
        <input type="hidden" name="orderTo" value="<?php print $param["orderTo"] ?>" />
        <input type="hidden" name="sPage" value="<?php print $param["sPage"] ?>" />
        <input type="hidden" name="coverNo" />

        <a href="javascript:form.act.value='coverEdit';form.submit();"><img src="./images/btn_enter.png"></a>

        <div class="search">
            <table border="0" cellpadding="2" cellSpacing="0">
                <tr>
                    <th>店舗名</th>
                    <td>
                        <?php
                        //機能追加　#29918 検索画面
                        $sql  = fnSqlStoreSelect(1);
                        $res  = mysqli_query($param["conn"], $sql);
                        ?>
                        <select name="sStore">
                            <option value="">----</option>
                            <?php
                            while ($row = mysqli_fetch_array($res)): ?>
                                <option name="sStore" value="<?php echo htmlspecialchars($row[0]); ?>"
                                    <?php echo ($row[0] === $param["sStore"]) ? 'selected' : ''; ?>>
                                    <?php echo ($row[0]); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>店舗担当者名</th>
                    <td colspan="6"><input type="text" name="sCover" value="<?php print $param["sCover"] ?>" size="30" /></td>
                </tr>
                <tr>
                    <th>携帯電話</th>
                    <td><input type="text" name="sMobile" value="<?php print $param["sMobile"] ?>" size="30" /></td>
                </tr>

            </table>
        </div>

        <input type="image" src="./images/btn_search.png" onclick="form.act.value='coverSearch';form.sPage.value=1;form.submit();" />

        <hr />

        <?php
        if ($_REQUEST['act'] == 'cover') {
            return;
        }

        $sql = fnSqlCoverList(0, $param);
        $res = mysqli_query($param["conn"], $sql);
        $row = mysqli_fetch_array($res);

        $count = $row[0];

        $sPage = fnPage($count, $param["sPage"], 'coverSearch');
        ?>

        <div class="list">
            <table border="0" cellpadding="5" cellspacing="1">
                <tr>
                    <th class="list_head">店舗名<?php fnOrder('STORE', 'coverSearch') ?></th>
                    <th class="list_head">店舗担当者名<?php fnOrder('COVER', 'coverSearch') ?></th>
                    <th class="list_head">携帯電話<?php fnOrder('MOBILE', 'coverSearch') ?></th>
                </tr>
                <?php
                $sql  = fnSqlCoverList(1, $param);
                $res  = mysqli_query($param["conn"], $sql);
                $i = 0;
                while ($row = mysqli_fetch_array($res)) {
                    $coverNo   = htmlspecialchars($row[0]);
                    $store   = htmlspecialchars($row[1]);
                    $cover     = htmlspecialchars($row[2]);
                    $mobile     = htmlspecialchars($row[3]);
                ?>
                    <tr>
                        <td class="list_td<?php print $i ?>"><?php print $store; ?></td>
                        <td class="list_td<?php print $i ?>"><a href="javascript:form.act.value='coverEdit';form.coverNo.value=<?php print $coverNo; ?>;form.submit();"><?php print $cover; ?></a></td>
                        <td class="list_td<?php print $i ?>"><?php print $mobile; ?></td>
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
//店舗担当者編集画面
//
function subCoverEditView($param)
{

?>
    <script type="text/javascript" src="./js/cover.js"></script>

    <h1>店舗担当者<?php print $param["purpose"] ?></h1>

    <form name="form" id="form" action="index.php" method="post">
        <input type="hidden" name="act" />
        <input type="hidden" name="sStore" value="<?php print $param["sStore"] ?>" />
        <input type="hidden" name="sCover" value="<?php print $param["sCover"] ?>" />
        <input type="hidden" name="sMobile" value="<?php print $param["sMobile"] ?>" />
        <input type="hidden" name="coverNo" value="<?php print $param["coverNo"] ?>" />

        <table border="0" cellpadding="5" cellspacing="1">
            <tr>
                <th>店舗名<span class="red">（必須）</span></th>
                <td>
                    <?php
                    //店舗名取得
                    $sql  = fnSqlStoreSelect(1);
                    $res  = mysqli_query($param["conn"], $sql);
                    ?>

                    <select name="store">
                        <option value="">----</option>
                        <?php while ($row = mysqli_fetch_array($res)): ?>
                            <option value="<?php echo htmlspecialchars($row[0]); ?>"
                                <?php echo ($row[0] === $param["store"]) ? 'selected' : ''; ?>>
                                <?php echo ($row[0]); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>店舗担当者名<span class="red">（必須）</span></th>
                <td><input type="text" name="cover" value="<?php print $param["cover"] ?>" /></td>
            </tr>
            <tr>
                <th>携帯電話</th>
                <td><input type="text" name="mobile" value="<?php print $param["mobile"] ?>" /></td>
            </tr>
        </table>

        <a href="javascript:fnCoverEditCheck();"><img src="./images/<?php print $param["btnImage"] ?>" /></a>
        <a href="javascript:form.act.value='coverSearch';form.submit();"><img src="./images/btn_return.png" /></a>
        <?php
        if ($param["coverNo"]) {
        ?>
            <a href="javascript:fnCoverDeleteCheck(<?php print $param["coverNo"] ?>);"><img src="./images/btn_del.png" /></a>
        <?php
        }
        ?>

    </form>

<?php
}
?>