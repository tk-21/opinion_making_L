// 反論削除
$(".objection-delete").on("click", function () {
    let url;

    if (confirm("削除してもよろしいですか？")) {
        let delete_id = $(this).data("id");
        let delete_type = $(this).data("type");

        if (delete_type === "objection") {
            url = "objections/" + delete_id;
        }

        if (delete_type === "counterObjection") {
            url = "counter_objections/" + delete_id;
        }

        let data = {
            delete_id: delete_id,
            delete_type: delete_type,
        };

        $.ajax({
            url: url,
            type: "post",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType: "json",
            data: data,
        }).then(
            //成功したとき
            function (data) {
                if (data) {
                    // クリックした要素の親要素を削除
                    $(this).parent().parent().remove();
                } else {
                    //削除に失敗
                    console.log("削除に失敗しました。");
                    alert("削除に失敗しました。");
                }
            }.bind(this), //thisを束縛
            //失敗したとき
            function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("削除に失敗しました。");
                console.log(XMLHttpRequest.status);
                console.log(textStatus);
                console.log(errorThrown);
                alert("削除に失敗しました。");
            }
        );
    }
});

// トピックのステータス変更
$(".home-topic-status").change(function () {
    let topic_id = $(this).data("id");
    let topic_status = $(this).parent().next().children().children("p").text();

    let data = {
        topic_id: topic_id,
        topic_status: topic_status,
    };

    $.ajax({
        url: 'topics/status',
        type: "post",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        dataType: "json",
        data: data,
    }).then(
        //成功したとき
        function (data) {
            if (data) {
                // テキストを取得
                let text = $(this)
                    .parent()
                    .next()
                    .children()
                    .children("p")
                    .text();

                // テキストの入れ替え
                text = text == "完了" ? "未完了" : "完了";

                // 入れ替えたテキストを設定
                $(this).parent().next().children().children("p").text(text);

                // クラス名を取得
                let style = $(this)
                    .parent()
                    .next()
                    .children()
                    .children("p")
                    .attr("class");

                // クラス名を変更
                style =
                    style == "home-topic-label _complete"
                        ? "home-topic-label _incomplete"
                        : "home-topic-label _complete";

                // クラス名を設定
                $(this)
                    .parent()
                    .next()
                    .children()
                    .children("p")
                    .attr("class", style);
            } else {
                //更新に失敗
                console.log("ステータス更新に失敗しました。");
                alert("ステータス更新に失敗しました。");
            }
        }.bind(this), //thisを束縛
        //失敗したとき
        function (XMLHttpRequest, textStatus, errorThrown) {
            console.log("ステータス更新に失敗しました。");
            console.log(XMLHttpRequest.status);
            console.log(textStatus);
            console.log(errorThrown);
            alert("ステータス更新に失敗。");
        }
    );
});
