// フロント側でチェックを行ったとしても、すり抜けられる可能性があるので、必ずリクエストを受け取ったサーバー側でもチェックを行うようにする
// フロント側のチェックはユーザビリティの向上のため

// 画面が初期化されたときに関数を呼ぶ
validate_form();

function validate_form() {
    // DOMとはHTMLをJavascriptで扱うためのオブジェクト
    // $はDOMであることの目印として付けている
    // クラスをキーにしてDOMを取ってくる
    // querySelectorAllで該当するものをすべて取ってくる
    const $forms = document.querySelectorAll(".validate-form");
    const $inputs = document.querySelectorAll(".validate-target");

    // $formが取れてこなかった場合は関数の処理を終了する
    if (!$forms) {
        return;
    }

    // $inputsから一つずつ$inputを取り出してくる
    // このようにすると、入力欄が増えても一つずつ関数を適用して処理できる
    for (const $input of $inputs) {
        // イベントを検知したときに実行する関数を登録
        // inputの関数の中で値が正しいかどうかを検知する
        $input.addEventListener("input", function (event) {
            // 変更を検知したDOMをcurrentTargetによって取得
            // $targetは値の変更を検知したDOM
            const $target = event.currentTarget;
            // $targetの次の要素を取得
            const $feedback = $target.nextElementSibling;

            const $mail = document.getElementById("mail-feedback");

            // イベントリスナーの中で呼ぶ
            for (const $form of $forms) {
                activateSubmitBtn($form);
            }

            // $feedback（$targetの次の要素）のクラスにinvalid-feedbackが付いているかどうかを確認しておく
            // もしエラー表示用のタグでない場合は後続の処理を行わないようにする
            if (!$feedback.classList.contains("invalid-feedback")) {
                return;
            }

            // 以下でバリデーションチェック
            if ($target.checkValidity()) {
                // checkValidが問題ない場合、is-validのクラスを付与する
                $target.classList.add("is-valid");
                // 合わせてis-invalidのクラスを削除する
                $target.classList.remove("is-invalid");

                // $feedbackの中身を初期化する
                $feedback.textContent = "";
            } else {
                // falseの場合は逆
                $target.classList.add("is-invalid");
                $target.classList.remove("is-valid");

                // falseの場合、何が問題なのかを判定する
                if ($target.validity.valueMissing) {
                    // $feedbackのテキストを書き換える
                    $feedback.textContent = "入力が必須です。";
                } else if ($target.validity.tooShort) {
                    $feedback.textContent =
                        $target.minLength +
                        "文字以上で入力してください。現在の文字数は" +
                        $target.value.length +
                        "文字です。";
                } else if ($target.validity.tooLong) {
                    $feedback.textContent =
                        $target.maxLength +
                        "文字以下で入力してください。現在の文字数は" +
                        $target.value.length +
                        "文字です。";
                } else if ($target.validity.patternMismatch) {
                    $feedback.textContent = "半角英数字で入力してください。";

                    $mail.textContent =
                        "メールアドレスは正しい形式で入力してください。";
                }
            }
        });
    }

    // ここで呼ぶことによって、初期状態のときにdisabledを付ける
    for (const $form of $forms) {
        activateSubmitBtn($form);
    }
}

// 入力値に問題がなければボタンを活性化する関数
function activateSubmitBtn($form) {
    // type属性にsubmitがついているものを取ってくる
    const $submitBtn = $form.querySelector('[type="submit"]');
    // formに対してcheckValidityを行った場合は、その入力欄すべてに問題がなければtrueを返す
    if ($form.checkValidity()) {
        // disableという属性を削除する
        $submitBtn.removeAttribute("disabled");
    } else {
        // disableという属性を付与する
        $submitBtn.setAttribute("disabled", true);
    }
}
