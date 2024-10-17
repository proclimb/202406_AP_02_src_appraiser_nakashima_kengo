<?php
//
//ログイン画面
//
function subLogin($errorMessage)
{
?>


	<div class="login_ttl">
		<img src="./images/logo.png">
	</div>


	<form name="form" action="index.php" method="post">
		<input type="hidden" name="act" value="loginCheck" />

		<div class="login_table">
			<table border="0" cellpadding="2" cellspacing="0">
				<tr>
					<th>ユーザーID</th>
					<td>
						<div class="id_box">
							<input type="text" name="id" style="ime-mode:disabled;" />
						</div>
					</td>
				</tr>
				<tr>
					<th>パスワード</th>
					<td>
						<div class="pass_box">
							<input class="inp_pw" type="password" name="pw" id="pw" />
							<i class="fa-solid fa-eye" id="eyeIcon"></i>
						</div>
					</td>
				</tr>
			</table>
			<?php if ($errorMessage): ?>
				<div style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></div>
			<?php endif; ?>
		</div>

		<div class="login_btn">
			<a href="javascript:form.submit();"><img src="./images/btn_login.png"></a>
		</div>
	</form>
	<script>
		const pwInp = document.getElementById('pw');
		const eyeIcon = document.getElementById('eyeIcon');
		// 入力時にアイコンの表示を切り替え
		pwInp.addEventListener('input', function() {
			if (pwInp.value.length > 0) {
				eyeIcon.style.display = 'inline-block';
				pwInp.classList.remove("inp_pw");
			} else {
				eyeIcon.style.display = 'none';
				pwInp.classList.add("inp_pw");
			}
		});
		// アイコンをクリックして表示/伏字を切り替え
		document.getElementById('eyeIcon').addEventListener('click', function() {
			if (pwInp.type === 'password') {
				pwInp.type = 'text';
				eyeIcon.classList.remove("fa-eye");
				eyeIcon.classList.add("fa-eye-slash");
			} else {
				pwInp.type = 'password';
				eyeIcon.classList.remove("fa-eye-slash");
				eyeIcon.classList.add("fa-eye");
			}
		});
	</script>
<?php
}





//
//ログイン確認
//
function subLoginCheck()
{
	$id = addslashes($_REQUEST['id']);
	$pw = addslashes($_REQUEST['pw']);

	$conn = fnDbConnect();

	$sql = fnSqlLogin($id);
	$res = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($res);

	if ($row[0] && password_verify($pw, $row['PASSWORD']) && $id === $row['ID']) {
		$_COOKIE['cUserNo']   = $row[0];
		$_COOKIE['authority'] = $row[1];
		$_REQUEST['act']      = 'menu';
	} else {
		$_REQUEST['msg'] = "ログインに失敗しました。再度お試しください。";
		$_REQUEST['act']  = 'reLogin';
	}
}
?>