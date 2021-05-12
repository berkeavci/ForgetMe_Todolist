<?php
    require "./db.php" ;
    $tsk =strval($_POST["task_n"]);
    $rs = $db->prepare("delete from tasktable where id = :tkn");
    $rs->execute(["tkn" => $tsk]);
?>