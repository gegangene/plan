<?php
    session_start();
    $lekcja="";
    $lek=$_SESSION['l'] or die('<h1>błąd id lekcji</h1><h2>[najprawdopodobniej dostałeś się tutaj przypadkiem bądź niechcący odświeżyłeś stronę]</h2><h3>wróć do <a href="./">planu</a>, bądź <a href="pd.html">strony głównej modułu modyfikacji pd</a></h3>');
    for($i=1;$i<strlen($lek);$i++)
    {
        $lekcja.=$lek[$i];
    }
    $pass=password_hash("/*your password*/", PASSWORD_DEFAULT);
    $plik=fopen("./sources/homework.txt", "r");
    $pd="";
    $pdspr="";
    while(!feof($plik))
    {
        $plekcja=fgets($plik);
        $pdtymczasowy=fgets($plik);
        if($lekcja.";\n"==$plekcja)
        {
            $pd=$pdtymczasowy;
            $pdspr=$plekcja;
        }
    }
    session_destroy();
    if($pd==""||$pd=="\n")
        die('ta lekcja nie ma pracy domowej dla się przypisanej. <a href="pd.html">wróć i dodaj pracę domową</a>. ID lekcji: '.$lek);
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <title>Edytor prac domowych wersja wczesna alpha: edycja pracy domowej z <?=$lek?></title>
        <meta charset="UTF-8">
        <link href="light.css?<?=time()?>" type="text/css" rel="stylesheet">
        <link href="style.css?<?=time()?>" type="text/css" rel="stylesheet">
        <link href="ggngnn.css?<?=time()?>" type="text/css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>
    <body>
        <h3><a href="index.php">powrót do planu</a></h3>
        <h3><a href="pd.html">powrót do zamiany/usuwania pd</a></h3>
        <p>Edycja pracy domowej z <?=$lekcja?></p>
        <form action="submit.php" method="post" class="padd05p">
            <input class="znikniete" type="text" name="lekcja" value="<?=$lek?>" readonly>
            <input class="znikniete" type="text" name="czyUsun" value="0" readonly>
            <input name="tresc" value="<?=$pd?>" type="text" style="min-width: 30%;">
            <input class="znikniete" type="password" name="pass" value="<?=$pass?>"><br/><br/>
            <input type="submit">
        </form>
        <footer class="padd1p">
            moduł modyfikacji pracy domowej v. 0.0.5
        </footer>
    </body>
</html>