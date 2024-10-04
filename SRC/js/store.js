//
//店舗チェック
//
function fnStoreEditCheck() {
    tmp = form.store.value;
    if (tmp.length == 0) {
        alert('店舗名を入力してください');
        return;
    }
    if (tmp.length > 100) {
        alert('店舗名は100文字以内で入力してください');
        return;
    }

    if (confirm('この内容で登録します。よろしいですか？')) {
        form.act.value = 'storeEditComplete';
        form.submit();
    }
}

function fnStoreDeleteCheck(no) {
    if (confirm('削除します。よろしいですか？')) {
        form.storeNo.value = no;
        form.act.value = 'storeDelete';
        form.submit();
    }
}