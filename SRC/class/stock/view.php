<?php
//
//仕入管理画面
//
function subStockView($param)
{
?>
	<script>
		var cal1 = new JKL.Calendar("cal1", "form", "sInsDTFrom");
		var cal2 = new JKL.Calendar("cal2", "form", "sInsDTTo");
		var cal3 = new JKL.Calendar("cal3", "form", "sVisitDTFrom");
		var cal4 = new JKL.Calendar("cal4", "form", "sVisitDTTo");
	</script>

	<h1>仕入管理一覧</h1>

	<form name="form" id="form" action="index.php" method="post">
		<input type="hidden" name="act" value="stockSearch" />
		<input type="hidden" name="orderBy" value="<?php print $param["orderBy"] ?>" />
		<input type="hidden" name="orderTo" value="<?php print $param["orderTo"] ?>" />
		<input type="hidden" name="sPage" value="<?php print $param["sPage"] ?>" />
		<input type="hidden" name="stockNo" />
		<input type="hidden" name="delStockList" />

		<a href="javascript:form.act.value='stockEdit';form.submit();"><img src="./images/btn_enter.png"></a>

		<div class="search">
			<table border="0" cellpadding="2" cellspacing="0">
				<tr>
					<th>除外</th>
					<td><input type="checkbox" name="sDel" value="0" <?php if ($param["sDel"] == 0) print ' checked="checked"' ?> /></td>
					<th>最寄駅</th>
					<td><input type="text" name="sStation" value="<?php print $param["sStation"] ?>" size="30" /></td>
				</tr>
				<tr>
					<th>日付</th>
					<td><input type="text" name="sInsDTFrom" value="<?php print $param["sInsDTFrom"] ?>" size="15" /> <a href="javascript:cal1.write();" onChange="cal1.getFormValue(); cal1.hide();"><img src="./images/b_calendar.png"></a><span id="cal1"></span>～
						<input type="text" name="sInsDTTo" value="<?php print $param["sInsDTTo"] ?>" size="15" /> <a href="javascript:cal2.write();" onChange="cal2.getFormValue(); cal2.hide();"><img src="./images/b_calendar.png"></a><span id="cal2"></span>
					</td>
					<th>距離</th>
					<td>
						<?php
						for ($i = 0; $i < 5; $i++) {
						?>
							<input type="checkbox" name="sDistance[]" value="<?php print $i + 1; ?>" <?php for ($j = 0; $j < 4; $j++) {
																											if ($param["sDistance"][$j] == $i + 1) print ' checked="checked"';
																										} ?> /> <?php print fnRankName($i) ?>
						<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<th>担当</th>
					<td><input type="text" name="sCharge" value="<?php print $param["sCharge"] ?>" size="30" /></td>
					<th>業者名</th>
					<td>
						<?php
						//機能追加　#29797 検索画面
						$sql  = fnSqlTradeSelect(1);
						$res  = mysqli_query($param["conn"], $sql);
						?>
						<select name="sAgent">
							<option value="">----</option>
							<?php
							while ($row = mysqli_fetch_assoc($res)): ?>
								<option name="sAgent" value="<?php echo htmlspecialchars($row['NAME']); ?>"
									<?php echo ($row['NAME'] === $param["sAgent"]) ? 'selected' : ''; ?>>
									<?php echo ($row['NAME']); ?>
								</option>
							<?php endwhile; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th>ランク</th>
					<td>
						<?php
						for ($i = 0; $i < 5; $i++) {
						?>
							<input type="checkbox" name="sRank[]" value="<?php print $i + 1; ?>" <?php for ($j = 0; $j < 5; $j++) {
																										if ($param["sRank"][$j] == $i + 1) print ' checked="checked"';
																									} ?> /> <?php print fnRankName($i) ?>
						<?php
						}
						?>
					</td>
					<th>店舗名</th>
					<td>
						<?php
						$sql  = fnSqlStoreSelect(1);
						$res  = mysqli_query($param["conn"], $sql);
						$stores = [];
						while ($row = mysqli_fetch_array($res)) {
							$stores[] = htmlspecialchars($row[0]);  //店舗名を配列に保存
						}
						?>
						<select name="sStore" id="storeSelect">
							<option value="">----</option>
							<?php foreach ($stores as $store): ?>
								<option value="<?php echo $store; ?>"
									<?php echo ($store === $param["sStore"]) ? 'selected' : ''; ?>>
									<?php echo $store; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th>物件名</th>
					<td><input type="text" name="sArticle" value="<?php print $param["sArticle"] ?>" size="50" /></td>
					<th>担当者名</th>
					<td>
						<select name="sCover" id="coverSelect" <?php echo !isset($_POST['sStore']) || $_POST['sStore'] === '' ? 'disabled' : ''; ?>>
							<option value="">----</option>
							<?php
							// 現在選択されている担当者名
							$selectedCover = isset($_POST['sCover']) ? htmlspecialchars($_POST['sCover']) : '';

							// 店舗名に対応する担当者名のマッピング
							$covers = [];
							foreach ($stores as $store) {
								$sql = fnSqlCoverSelectByStore($store); // 店舗名に基づく担当者名取得のSQL関数
								$res = mysqli_query($param["conn"], $sql);
								while ($row = mysqli_fetch_array($res)) {
									$cover = htmlspecialchars($row[0]);
									$covers[$store][] = $cover; // 店舗ごとの担当者名を保存
								}
							}

							// 店舗名が選択されたときの処理
							?>
							<script>
								const covers = <?php echo json_encode($covers); ?>; // JSON形式でクライアントに渡す
								document.getElementById("storeSelect").addEventListener("change", function() {
									const store = this.value;
									const coverSelect = document.getElementById("coverSelect");
									coverSelect.innerHTML = "<option value=''>-----</option>"; // 初期化
									if (covers[store]) {
										covers[store].forEach(function(cover) {
											const option = document.createElement("option");
											option.value = cover;
											option.textContent = cover;
											coverSelect.appendChild(option);
										});
										coverSelect.disabled = false; // 店舗名が選択された場合は有効化
									} else {
										coverSelect.disabled = true; // 店舗名が選択されていない場合は無効化
									}
								});
							</script>

							<?php
							// 初期値として選択されている担当者名を設定
							foreach ($covers as $store => $coverList) {
								if ($store === $param["sStore"]) {
									foreach ($coverList as $cover) {
										$selected = ($cover === $selectedCover) ? 'selected' : '';
										echo "<option value=\"$cover\" $selected>$cover</option>";
									}
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th>物件名（よみ）</th>
					<td><input type="text" name="sArticleFuri" value="<?php print $param["sArticleFuri"] ?>" size="50" /></td>
					<th>内見</th>
					<td><input type="text" name="sVisitDTFrom" value="<?php print $param["sVisitDTFrom"] ?>" size="15" /> <a href="javascript:cal3.write();" onChange="cal3.getFormValue(); cal3.hide();"><img src="./images/b_calendar.png"></a><span id="cal3"></span>～
						<input type="text" name="sVisitDTTo" value="<?php print $param["sVisitDTTo"] ?>" size="15" /> <a href="javascript:cal4.write();" onChange="cal4.getFormValue(); cal4.hide();"><img src="./images/b_calendar.png"></a><span id="cal4"></span>
					</td>
				</tr>
				<tr>
					<th>面積</th>
					<td><input type="text" name="sAreaFrom" value="<?php print $param["sAreaFrom"] ?>" size="10" /> ～
						<input type="text" name="sAreaTo" value="<?php print $param["sAreaTo"] ?>" size="10" />
					</td>
					<th>仕入経緯</th>
					<td>
						<?php
						for ($i = 0; $i < 6; $i++) {
						?>
							<input type="checkbox" name="sHow[]" value="<?php print $i + 1; ?>" <?php for ($j = 0; $j < 6; $j++) {
																									if ($param["sHow"][$j] == $i + 1) print ' checked="checked"';
																								} ?> /> <?php print fnHowName($i); ?>
						<?php
							if ($i == 2) {
								print "<br />\n";
							}
						}
						?>
					</td>
				</tr>
			</table>
		</div>

		<input type="image" src="./images/btn_search.png" onclick="form.act.value='stockSearch'; form.sPage.value=1; form.submit();" />

		<hr />

		<?php
		if ($_REQUEST['act'] == 'stock') {
			return;
		}

		$sql = fnSqlStockList(0, $param);
		$res = mysqli_query($param["conn"], $sql);
		$row = mysqli_fetch_array($res);

		$count = $row[0];

		$sPage = fnPage($count, $param["sPage"], 'stockSearch');
		?>

		<?php //機能追加：CSV出力
		$sql  = fnSqlStockList(1, $param);
		$res  = mysqli_query($param["conn"], $sql);

		while ($row = mysqli_fetch_array($res)) {
			$stockNos[] = htmlspecialchars($row[0]);
		}
		$stockNosText = implode(",", $stockNos);
		//print_r($stockNos);
		?>
		<input type="hidden" name="csvno" value=<?php echo $count ?> />
		<input type="hidden" name="csvdate" value=<?php echo $stockNosText ?> />
		<input type="image" src="./images/btn_csv.png" onclick="form.act.value='stockCsv'; " />
		<?php //<input type="image" src="./images/btn_csv.png" onclick="form.act.value='stockSearch'; form.sPage.value=1; form.submit();" />
		?>

		<div class=" list">
			<table border="0" cellpadding="5" cellspacing="1">
				<tr>
					<th class="list_head">担当<?php fnOrder('CHARGE', 'stockSearch') ?></th>
					<th class="list_head">ランク<?php fnOrder('`RANK`', 'stockSearch') ?></th>
					<th class="list_head">日付<?php fnOrder('INSDT', 'stockSearch') ?></th>
					<th class="list_head">物件名<?php fnOrder('ARTICLE', 'stockSearch') ?></th>
					<th class="list_head">部屋<?php fnOrder('ROOM', 'stockSearch') ?></th>
					<th class="list_head">面積<?php fnOrder('AREA', 'stockSearch') ?></th>
					<th class="list_head">最寄駅<?php fnOrder('STATION', 'stockSearch') ?></th>
					<th class="list_head">距離<?php fnOrder('DISTANCE', 'stockSearch') ?></th>
					<th class="list_head">業者名<?php fnOrder('AGENT', 'stockSearch') ?></th>
					<th class="list_head">店舗名<?php fnOrder('STORE', 'stockSearch') ?></th>
					<th class="list_head">担当者名<?php fnOrder('COVER', 'stockSearch') ?></th>
					<th class="list_head">内見<?php fnOrder('VISITDT', 'stockSearch') ?></th>
					<th class="list_head">机上金額<?php fnOrder('DESKPRICE', 'stockSearch') ?></th>
					<th class="list_head">売主希望金額<?php fnOrder('VENDORPRICE', 'stockSearch') ?></th>
					<th class="list_head">備考<?php fnOrder('NOTE', 'stockSearch') ?></th>
				</tr>
				<?php
				$sql  = fnSqlStockList(1, $param);
				$res  = mysqli_query($param["conn"], $sql);
				$i = 0;
				while ($row = mysqli_fetch_array($res)) {
					$stockNo     = htmlspecialchars($row[0]);
					$charge      = htmlspecialchars($row[1]);
					$rank        = fnRankName(htmlspecialchars($row[2] - 1));
					$insDT       = htmlspecialchars($row[3]);
					$article     = htmlspecialchars($row[4]);
					$articleFuri = htmlspecialchars($row[5]);
					$room        = htmlspecialchars($row[6]);
					$area        = htmlspecialchars($row[7]);
					$station     = htmlspecialchars($row[8]);
					$distance    = fnRankName(htmlspecialchars($row[9] - 1));
					$agent       = htmlspecialchars($row[10]);
					$store       = htmlspecialchars($row[11]);
					$cover       = htmlspecialchars($row[12]);
					$visitDT     = htmlspecialchars($row[13]);
					$deskPrice   = htmlspecialchars(fnNumFormat($row[14]));
					$vendorPrice = htmlspecialchars(fnNumFormat($row[15]));
					$note        = htmlspecialchars($row[16]);
				?>
					<tr>
						<td class="list_td<?php print $i; ?>"><?php print $charge; ?></td>
						<td class="list_td<?php print $i; ?>"><?php print $rank; ?></td>
						<td class="list_td<?php print $i; ?>"><?php print $insDT; ?></td>
						<td class="list_td<?php print $i; ?>"><a href="javascript:form.act.value='stockEdit';form.stockNo.value=<?php print $stockNo; ?>;form.submit();"><?php print $article; ?></a></td>
						<td class="list_td<?php print $i; ?>"><?php print $room; ?></td>
						<td class="list_td<?php print $i; ?>" align="right"><?php print $area; ?></td>
						<td class="list_td<?php print $i; ?>"><?php print $station; ?></td>
						<td class="list_td<?php print $i; ?>"><?php print $distance; ?></td>
						<td class="list_td<?php print $i; ?>"><?php print $agent; ?></td>
						<td class="list_td<?php print $i; ?>"><?php print $store; ?></td>
						<td class="list_td<?php print $i; ?>"><?php print $cover; ?></td>
						<td class="list_td<?php print $i; ?>"><?php print $visitDT; ?></td>
						<td class="list_td<?php print $i; ?>" align="right"><?php print $deskPrice; ?></td>
						<td class="list_td<?php print $i; ?>" align="right"><?php print $vendorPrice; ?></td>
						<td class="list_td<?php print $i; ?>"><?php print $note; ?></td>
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
//仕入管理編集画面
//
function subStockEditView($param)
{

?>
	<script type="text/javascript" src="./js/stock.js"></script>
	<script type="text/javascript" src="./js/jquery-1.4.min.js"></script>
	<script type="text/javascript" src="./js/auto_ruby.js"></script>
	<script>
		var cal1 = new JKL.Calendar("cal1", "form", "visitDT");
	</script>

	<h1>仕入<?php print $param["purpose"] ?></h1>

	<form name="form" id="form" action="index.php" method="post">
		<input type="hidden" name="act" />
		<input type="hidden" name="sDel" value="<?php print $param["sDel"] ?>" />
		<input type="hidden" name="sInsDTFrom" value="<?php print $param["sInsDTFrom"] ?>" />
		<input type="hidden" name="sInsDTTo" value="<?php print $param["sInsDTTo"] ?>" />
		<input type="hidden" name="sCharge" value="<?php print $param["sCharge"] ?>" />
		<input type="hidden" name="sRank" value="<?php print $param["sRank"] ?>" />
		<input type="hidden" name="sArticle" value="<?php print $param["sArticle"] ?>" />
		<input type="hidden" name="sArticleFuri" value="<?php print $param["sArticleFuri"] ?>" />
		<input type="hidden" name="sAreaFrom" value="<?php print $param["sAreaFrom"] ?>" />
		<input type="hidden" name="sAreaTo" value="<?php print $param["sAreaTo"] ?>" />
		<input type="hidden" name="sStation" value="<?php print $param["sStation"] ?>" />
		<input type="hidden" name="sDistance" value="<?php print $param["sDistance"] ?>" />
		<input type="hidden" name="sAgent" value="<?php print $param["sAgent"] ?>" />
		<input type="hidden" name="sStore" value="<?php print $param["sStore"] ?>" />
		<input type="hidden" name="sCover" value="<?php print $param["sCover"] ?>" />
		<input type="hidden" name="sVisitDTFrom" value="<?php print $param["sVisitDTFrom"] ?>" />
		<input type="hidden" name="sVisitDTTo" value="<?php print $param["sVisitDTTo"] ?>" />
		<input type="hidden" name="sHow" value="<?php print $param["sHow"] ?>" />
		<input type="hidden" name="orderBy" value="<?php print $param["orderBy"] ?>" />
		<input type="hidden" name="orderTo" value="<?php print $param["orderTo"] ?>" />
		<input type="hidden" name="sPage" value="<?php print $param["sPage"] ?>" />
		<input type="hidden" name="stockNo" value="<?php print $param["stockNo"] ?>" />

		<table border="0" cellpadding="5" cellspacing="1">
			<tr>
				<th>除外</th>
				<td><input type="radio" name="del" value="1" checked /> 非除外
					<input type="radio" name="del" value="0" /> 除外
				</td>
			</tr>
			<tr>
				<th>担当</th>
				<td><input type="text" name="charge" value="<?php print $param["charge"] ?>" /></td>
			</tr>
			<tr>
				<th>ランク</th>
				<td>
					<?php
					if (!$param["stockNo"]) {
						$param["rank"] = 1;
					}
					for ($i = 0; $i < 5; $i++) {
						$check = '';
						if (($param["rank"] - 1) == $i) {
							$check = 'checked = "checked"';
						}
					?>
						<input type="radio" name="rank" value="<?php print $i + 1; ?>" <?php print $check; ?> /> <?php print fnRankName($i); ?>
					<?php
					}
					?>
				</td>
			</tr>
			<tr>
				<th>物件名<span class="red">（必須）</span></th>
				<td><input type="text" name="article" id="name" value="<?php print $param["article"] ?>" /></td>
			</tr>
			<tr>
				<th>物件名（よみ）</th>
				<td><input type="text" name="articleFuri" id="ruby" value="<?php print $param["articleFuri"] ?>" /></td>
			</tr>
			<tr>
				<th>部屋</th>
				<td><input type="text" name="room" value="<?php print $param["room"] ?>" /></td>
			</tr>
			<tr>
				<th>面積</th>
				<td><input type="text" name="area" value="<?php print $param["area"] ?>" />㎡</td>
			</tr>
			<tr>
				<th>最寄駅</th>
				<td><input type="text" name="station" value="<?php print $param["station"] ?>" /></td>
			</tr>
			<tr>
				<th>距離</th>
				<td>
					<?php
					if (!$param["stockNo"]) {
						$param["distance"] = 1;
					}
					for ($i = 0; $i < 4; $i++) {
						$check = '';
						if (($param["distance"] - 1) == $i) {
							$check = 'checked = "checked"';
						}
					?>
						<input type="radio" name="distance" value="<?php print $i + 1; ?>" <?php print $check; ?> /> <?php print fnDistanceName($i); ?>
					<?php
					}
					?>
				</td>
			</tr>
			<tr>
				<th>業者名</th>
				<td>
					<?php
					//業者名取得
					$sql  = fnSqlTradeSelect(1);
					$res  = mysqli_query($param["conn"], $sql);
					?>

					<select name="agent">
						<option value="">----</option>
						<?php while ($row = mysqli_fetch_assoc($res)): ?>
							<option value="<?php echo htmlspecialchars($row['NAME']); ?>"
								<?php echo ($row['NAME'] === $param["agent"]) ? 'selected' : ''; ?>>
								<?php echo ($row['NAME']); ?>
							</option>
						<?php endwhile; ?>
					</select>
					<?php
					//業者名の総件数
					$sql  = fnSqlTradeSelect(0);
					$res  = mysqli_query($param["conn"], $sql);
					$row = mysqli_fetch_array($res);
					$count = $row[0];
					?>

				</td>
			</tr>
			<tr>
				<th>店舗名</th>
				<td>
					<?php
					$sql  = fnSqlStoreSelect(1);
					$res  = mysqli_query($param["conn"], $sql);
					$stores = [];
					while ($row = mysqli_fetch_array($res)) {
						$stores[] = htmlspecialchars($row[0]);  //店舗名を配列に保存
					}
					?>
					<select name="store" id="storeSelect">
						<option value="">----</option>
						<?php foreach ($stores as $store): ?>
							<option value="<?php echo $store; ?>"
								<?php echo ($store === $param["store"]) ? 'selected' : ''; ?>>
								<?php echo $store; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th>担当者名</th>
				<td>
					<select name="cover" id="coverSelect" <?php echo !isset($param["store"]) || $param["store"] === '' ? 'disabled' : ''; ?>>
						<option value="">----</option>
						<?php
						// 店舗名が選択されている場合、その店舗の担当者名を表示
						if (isset($param["store"]) && $param["store"] !== '') {
							$sql = fnSqlCoverSelectByStore($param["store"]); // 店舗名に基づく担当者名取得のSQL関数
							$res = mysqli_query($param["conn"], $sql);

							while ($row = mysqli_fetch_array($res)) {
								$cover = htmlspecialchars($row[0]);
								$selected = ($cover === (isset($param["cover"]) ? $param["cover"] : '')) ? 'selected' : '';
								echo "<option value=\"$cover\" $selected>$cover</option>";
							}
						}
						?>
					</select>
				</td>
			</tr>

			<script>
				// 店舗名に対応する担当者名のマッピング
				const covers = <?php
								$coversArray = [];
								foreach ($stores as $store) {
									$sql = fnSqlCoverSelectByStore($store);
									$res = mysqli_query($param["conn"], $sql);
									$coversArray[$store] = [];
									while ($row = mysqli_fetch_array($res)) {
										$coversArray[$store][] = htmlspecialchars($row[0]);
									}
								}
								echo json_encode($coversArray);
								?>;

				// 店舗名が選択されたときの処理
				document.getElementById('storeSelect').addEventListener('change', function() {
					const store = this.value;
					const coverSelect = document.getElementById('coverSelect');
					coverSelect.innerHTML = '<option value="">----</option>'; // 初期化

					if (covers[store]) {
						covers[store].forEach(function(cover) {
							const option = document.createElement('option');
							option.value = cover;
							option.textContent = cover;
							coverSelect.appendChild(option);
						});
						coverSelect.disabled = false; // 店舗名が選択された場合は有効化
					} else {
						coverSelect.disabled = true; // 店舗名が選択されていない場合は無効化
					}
				});
			</script>
			</td>
			</tr>
			<tr>
				<th>内見</th>
				<td><input type="text" name="visitDT" value="<?php print $param["visitDT"] ?>" /> <a href="javascript:cal1.write();" onChange="cal1.getFormValue(); cal1.hide();"><img src="./images/b_calendar.png"></a><span id="cal1"></span></td>
			</tr>
			<tr>
				<th>机上金額</th>
				<td><input type="text" name="deskPrice" value="<?php print $param["deskPrice"] ?>" />万円</td>
			</tr>
			<tr>
				<th>売主希望金額</th>
				<td><input type="text" name="vendorPrice" value="<?php print $param["vendorPrice"] ?>" />万円</td>
			</tr>
			<tr>
				<th>備考</th>
				<td><textarea name="note" cols="50" rows="10"><?php print $param["note"] ?></textarea></td>
			</tr>
			<tr>
				<th>仕入経緯</th>
				<td>
					<?php
					if (!$param["stockNo"]) {
						$param["how"] = 1;
					}
					for ($i = 0; $i < 6; $i++) {
						$check = '';
						if (($param["how"] - 1) == $i) {
							$check = 'checked = "checked"';
						}
					?>
						<br />
						<input type="radio" name="how" value="<?php print $i + 1; ?>" <?php print $check; ?> /> <?php print fnHowName($i); ?>
					<?php
					}
					?>
				</td>
			</tr>

		</table>

		<a href="javascript:fnStockEditCheck();"><img src="./images/<?php print $param["btnImage"] ?>" /></a>　
		<a href="javascript:form.act.value='stockSearch';form.submit();"><img src="./images/btn_return.png" /></a>
		<?php
		if ($param["stockNo"]) {
		?>
			<a href="javascript:fnStockDeleteCheck(<?php print $param["stockNo"] ?>);"><img src="./images/btn_del.png" /></a>
		<?php
		}
		?>

	</form>
<?php
}
?>