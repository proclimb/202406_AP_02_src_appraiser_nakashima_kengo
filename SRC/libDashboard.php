<?php
function subDashboard()
{
    $conn = fnDbConnect(); //DBに接続
    subMenu();
?>

    <h1>ダッシュボード</h1>

    <div class="board_list">
        <table border="0" cellpadding="5" cellspacing="1">
            <p>仕入上位5位(金額順)</p>
            <thead>
                <tr>
                    <th>担当者</th>
                    <th>机上金額</th>
                    <th>件数</th>
                </tr>
                <?php
                $sql = fnSqlStockAmountRank();
                $res = mysqli_query($conn, $sql);
                $i = 0;
                while ($row = mysqli_fetch_array($res)):
                    $cover = htmlspecialchars($row[0]);
                    $amount = htmlspecialchars($row[1]);
                    $number = htmlspecialchars($row[2]);
                ?>
                    <tr>
                        <td class="list_td<?php print $i ?>"><?php print $cover ?></td>
                        <td class="list_td<?php print $i ?>"><?php print $amount ?></td>
                        <td class="list_td<?php print $i ?>"><?php print $number ?></td>
                    </tr>
                <?php
                    $i = ($i + 1) % 2;
                endwhile;
                ?>
            </thead>
        </table>
    </div>

    <div class="board_list">
        <table border="0" cellpadding="5" cellspacing="1">
            <p>仕入上位5位(件数順)</p>
            <thead>
                <tr>
                    <th>担当者</th>
                    <th>机上金額</th>
                    <th>件数</th>
                </tr>
                <?php
                $sql = fnSqlStockNumberRank();
                $res = mysqli_query($conn, $sql);
                $i = 0;
                while ($row = mysqli_fetch_array($res)):
                    $cover = htmlspecialchars($row[0]);
                    $number = htmlspecialchars($row[1]);
                    $amount = htmlspecialchars($row[2]);
                ?>
                    <tr>
                        <td class="list_td<?php print $i ?>"><?php print $cover ?></td>
                        <td class="list_td<?php print $i ?>"><?php print $number ?></td>
                        <td class="list_td<?php print $i ?>"><?php print $amount ?></td>
                    </tr>
                <?php
                    $i = ($i + 1) % 2;
                endwhile;
                ?>
            </thead>
        </table>
    </div>

    <div class="board_list">
        <table border="0" cellpadding="5" cellspacing="1">
            <p>売上上位5位(物件終了)（金額順）</p>
            <thead>
                <tr>
                    <th>営業担当者</th>
                    <th>販売予定額</th>
                    <th>件数</th>
                </tr>
                <?php
                $sql = fnSqlSellAmountRank();
                $res = mysqli_query($conn, $sql);
                $i = 0;
                while ($row = mysqli_fetch_array($res)):
                    $sellCharge = htmlspecialchars($row[0]);
                    $amountSellPrice = htmlspecialchars($row[1]);
                    $count = htmlspecialchars($row[2]);
                ?>
                    <tr>
                        <td class="list_td<?php print $i ?>"><?php print $sellCharge ?></td>
                        <td class="list_td<?php print $i ?>"><?php print $amountSellPrice ?></td>
                        <td class="list_td<?php print $i ?>"><?php print $count ?></td>
                    </tr>
                <?php
                    $i = ($i + 1) % 2;
                endwhile;
                ?>
            </thead>
        </table>
    </div>

    <div class="board_list">
        <table border="0" cellpadding="5" cellspacing="1">
            <p>売上上位5位(物件終了)（件数順）</p>
            <thead>
                <tr>
                    <th>営業担当者</th>
                    <th>件数</th>
                    <th>販売予定額</th>
                </tr>
                <?php
                $sql = fnSqlSellAmountNumber();
                $res = mysqli_query($conn, $sql);
                $i = 0;
                while ($row = mysqli_fetch_array($res)):
                    $sellCharge = htmlspecialchars($row[0]);
                    $amountSellPrice = htmlspecialchars($row[1]);
                    $count = htmlspecialchars($row[2]);
                ?>
                    <tr>
                        <td class="list_td<?php print $i ?>"><?php print $sellCharge ?></td>
                        <td class="list_td<?php print $i ?>"><?php print $count ?></td>
                        <td class="list_td<?php print $i ?>"><?php print $amountSellPrice ?></td>
                    </tr>
                <?php
                    $i = ($i + 1) % 2;
                endwhile;
                ?>
            </thead>
        </table>
    </div>

    <div class="board_list">
        <table border="0" cellpadding="5" cellspacing="1">
            <p>本日案内一覧</p>
            <thead>
                <tr>
                    <th>営業担当者</th>
                    <th>案内日</th>
                    <th>物件</th>
                </tr>
                <?php
                $sql = fnSqlTodaysGuide();
                $res = mysqli_query($conn, $sql);
                $i = 0;
                $today = date('Y-m-d');
                while ($row = mysqli_fetch_array($res)):
                    $sellCharge = htmlspecialchars($row[0]);
                    $guideStart = htmlspecialchars($row[1]);
                    $guideEnd = htmlspecialchars($row[2]);
                    $article = htmlspecialchars($row[3]);
                ?>
                    <?php if ($guideStart == $today) { ?>
                        <tr>
                            <td class="list_td<?php print $i ?>"><?php print $sellCharge ?></td>
                            <td class="list_td<?php print $i ?>"><?php print $guideStart . "～" . $guideEnd ?></td>
                            <td class="list_td<?php print $i ?>"><?php print $article ?></td>
                        </tr>
                <?php
                    }
                    $i = ($i + 1) % 2;
                endwhile;
                ?>
            </thead>
        </table>
    </div>

<?php
}
?>