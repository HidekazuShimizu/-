<?php
// DB接続用ファイルとGETとPOSTの値をチェックするファイルを読み込み
require_once __DIR__ . '/../db_config.php';
require_once __DIR__ . '/../get_post_check.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>倉庫クエアップデート</title>
	<link rel="icon" href="../img/favicon.ico">
	<link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="main">
    <h2>倉庫クエ<?= $step; ?>（敵一覧）データ登録</h2>
    <p>
    <form method="post" action="insert_souko_kue.php?step=<?= htmlspecialchars($step, ENT_QUOTES) ?>">
        <table border="1">
            <tr>
                <th>ドロップアイテム</th><th>ドロップする敵</th><th>エリア１</th><th>エリア２</th><th>x座標</th><th>y座標</th><th>高度</th><th>更新</th>
            </tr>
            <tr>
                <td>
<?php
echo '<select name="drop_item">';
echo '<option hidden>選択してください</option>';
foreach ($drop_item_name as $key => $value) {
    echo '<option value="' . $key . '">' . $value . '</option>';
}
echo '</select>';
?>
                </td>
                <td>
                    <input type="text" name="drop_enemy" size="20" maxlength="10" required>
                </td>
                <td>
                    <input type="text" name="drop_area1" size="10" maxlength="10">
                </td>
                <td>
                    <input type="text" name="drop_area2" size="10" maxlength="10">
                </td>
                <td>
                    <input type="number" name="x" min="0" max="999">
                </td>
                <td>
                    <input type="number" name="y" min="0" max="999">
                </td>
                <td>
                    <input type="number" name="height" min="0" max="99">
                </td>
                <td>
                    <input type="submit" value="登録">
                </td>
            </tr>
        </table>
    </form>
    <form action="update.html">
        <input type="submit" value="戻る">
    </form>
<?php
try {
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (empty($search)) {
       $sql = 'SELECT * FROM souko_kue WHERE step = ?';
       $stmt = $dbh->prepare($sql);
       $stmt->bindValue(1, $step, PDO::PARAM_INT);
    } else {
        foreach ($drop_item_name as $key => $value) {
            if (str_contains($value, $search)) {
                $cnt = $key;
                break;
            }
/*
            if (preg_match('/'.$search.'/', $value)) {
                $cnt = $key;
                break;
            }
*/
        }
        $sql = 'SELECT * FROM souko_kue WHERE step = ? and (drop_item = ? or drop_enemy like ? or drop_area1 like ? or drop_area2 like ?)';
        $search_like = '%' . $search . '%';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $step, PDO::PARAM_INT);
        if (isset($cnt)) {
            $stmt->bindValue(2, $cnt, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(2, NULL, PDO::PARAM_INT);
        }
        $stmt->bindValue(3, $search_like, PDO::PARAM_STR);
        $stmt->bindValue(4, $search_like, PDO::PARAM_STR);
        $stmt->bindValue(5, $search_like, PDO::PARAM_STR);
    }
    //echo $sql . '<br>';
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($result);
    //var_dump($search);
    echo '<table>' . PHP_EOL;
    echo '<tr>' . PHP_EOL;
    echo '<th>' . PHP_EOL;
    echo '<form method="get" action="souko_kue.php?step=' . htmlspecialchars($step, ENT_QUOTES) . '&search=' . htmlspecialchars($search, ENT_QUOTES) . '">' . PHP_EOL;
    echo '<input type="text" class="reset_place" name="search" value="' . htmlspecialchars($search, ENT_QUOTES) . '" size="22" maxlength="10">' . PHP_EOL;
    echo '<input type="hidden" name="step" value="' . htmlspecialchars($step, ENT_QUOTES) .'">' . PHP_EOL;
    echo '<input type="submit" value="検索">' . PHP_EOL;
    echo '<button class="reset">リセット</button>' . PHP_EOL;
    echo '</form>' . PHP_EOL;
    echo '</th>' . PHP_EOL;
    echo '</tr>' . PHP_EOL;
    echo '</table>' . PHP_EOL;
    echo '<table border="1">' . PHP_EOL;
    echo '<tr>' . PHP_EOL;
    echo '<th>ドロップアイテム</th><th>ドロップする敵</th><th>エリア１</th><th>エリア２</th><th>座標(付近)</th><th>更新</th>' . PHP_EOL;
    echo '</tr>' . PHP_EOL;
    foreach ($result as $row) {
        //var_dump($row);
        echo '<form method="post" action="update_souko_kue.php?step=' . htmlspecialchars($step, ENT_QUOTES) . '">' . PHP_EOL;
        echo '<tr>' . PHP_EOL;
        echo '<td>' . $drop_item_name[(int)$row['drop_item']] . '</td>' . PHP_EOL;
        echo '<td>' . htmlspecialchars($row['drop_enemy']) . '</td>' . PHP_EOL;
        echo '<td>' . htmlspecialchars($row['drop_area1']) . '</td>' . PHP_EOL;
        echo '<td>' . htmlspecialchars($row['drop_area2']) . '</td>' . PHP_EOL;
        echo '<td>(' . htmlspecialchars($row['x'], ENT_QUOTES) . ',' . htmlspecialchars($row['y'], ENT_QUOTES) . ')↑' . htmlspecialchars($row['height'], ENT_QUOTES) . '</td>' . PHP_EOL;
        echo '<td><input type="submit" value="更新"></td>' . PHP_EOL;
        echo '</tr>' . PHP_EOL;
        echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id'], ENT_QUOTES) . '">';
        echo '</form>' . PHP_EOL;
    }
    echo '</table>' . PHP_EOL;
    $dbh = null;
} catch (PDOException $e) {
    echo 'エラー発生：表示に失敗しました。';
    exit;
}
?>
    </p>
    </div>
	<script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/souko_quest.js"></script>
</body>
</html>