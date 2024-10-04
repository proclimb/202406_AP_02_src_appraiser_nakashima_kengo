//
//店舗チェック
//
function fnCoverEditCheck() {
    tmp = form.store.value;
    if (tmp.length == 0) {
        alert('店舗名を選択してください');
        return;
    }
    tmp = form.cover.value;
    if (tmp.length == 0) {
        alert('店舗担当者名を入力してください');
        return;
    }
    if (tmp.length > 100) {
        alert('店舗担当者名は100文字以内で入力してください');
        return;
    }
    tmp = form.mobile.value;
    if (tmp.length > 100) {
        alert('携帯電話は100文字以内で入力してください');
        return;
    }

    if (confirm('この内容で登録します。よろしいですか？')) {
        form.act.value = 'coverEditComplete';
        form.submit();
    }
}

function fnCoverDeleteCheck(no) {
    if (confirm('削除します。よろしいですか？')) {
        form.coverNo.value = no;
        form.act.value = 'coverDelete';
        form.submit();
    }
}