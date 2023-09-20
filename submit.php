<?php
    if($_POST['pass']=="/*your password*/"||password_verify("/*your password*/",$_POST['pass']))
    {
        if($_POST["czyUsun"]==0)
        {
            $lek=$_POST['lekcja'];
            $lekNum=$lek[0];
            $lekNaz="";
            for($i=1; $i<strlen($lek); $i++)
            {
                $lekNaz=$lekNaz.$lek[$i];
            }
            $lekNaz=$lekNaz.";\n";
            $tresc=$_POST['tresc'];
            $tresc=$tresc."\n";
            //prace domowe - tworzenie tabeli
            $hmwrks=array("","","","","","","","","");
            $lssns=array("","","","","","","","","");
            //prace domowe - otwieranie pliku
            $source = fopen("./sources/homework.txt", "r") or die("plik z pracami domowymi nie istnieje bądź jest uszkodzony");

            //odczytywanie zawartości pliku
            for($i=0; $i<9; $i++)
            {
                $lekcja0 = fgets($source);
                for($nn=0; $nn<strlen($lekcja0); $nn++)
                {
                    if($lekcja0[$nn]==";")
                        break;
                    else
                        $lssns[$i]=$lssns[$i].$lekcja0[$nn];
                }
                $hmwrks[$i] = fgets($source);
            }
            fclose($source);

            $source = fopen("./sources/homework.txt", "w") or die("plik z pracami domowymi nie istnieje bądź jest uszkodzony");
            for($i=0; $i<9; $i++)
            {
                if($lekNum-1==$i)
                {
                    fwrite($source, $lekNaz);
                    fwrite($source, $tresc);
                }
                else
                {
                    if($hmwrks[$i]=="\n"&&$lssns[$i][0]!="/")
                        $lssns[$i]="/".$lssns[$i];
                    $pom=$lssns[$i].";\n";
                    fwrite($source, $pom);
                    fwrite($source, $hmwrks[$i]);
                }
            }
            fclose($source);
            header("Location: pd.html");
        }
        else if($_POST['czyUsun']==1)
        {
            $lek=$_POST['lekcja'];
            $lekNum=$lek[0];
            $lekNaz="";
            for($i=1; $i<strlen($lek); $i++)
            {
                $lekNaz=$lekNaz.$lek[$i];
            }
            $lekNaz=$lekNaz.";\n";
            //prace domowe - tworzenie tabeli
            $hmwrks=array("","","","","","","","","");
            $lssns=array("","","","","","","","","");
            //prace domowe - otwieranie pliku
            $source = fopen("./sources/homework.txt", "r") or die("plik z pracami domowymi nie istnieje bądź jest uszkodzony");

            //odczytywanie zawartości pliku
            for($i=0; $i<9; $i++)
            {
                $lekcja0 = fgets($source);
                for($nn=0; $nn<strlen($lekcja0); $nn++)
                {
                    if($lekcja0[$nn]==";")
                        break;
                    else
                        $lssns[$i]=$lssns[$i].$lekcja0[$nn];
                }
                $hmwrks[$i] = fgets($source);
            }
            fclose($source);

            $source = fopen("./sources/homework.txt", "w") or die("plik z pracami domowymi nie istnieje bądź jest uszkodzony");
            for($i=0; $i<9; $i++)
            {
                if($lekNum-1==$i)
                {
                    fwrite($source, "/".$lekNaz);
                    fwrite($source, "\n");
                }
                else
                {
                    if($hmwrks[$i]=="\n"&&$lssns[$i][0]!="/")
                        $lssns[$i]="/".$lssns[$i];
                    $pom=$lssns[$i].";\n";
                    fwrite($source, $pom);
                    fwrite($source, $hmwrks[$i]);
                }
            }
            fclose($source);
            header("Location: pd.html");
        }
        else if($_POST['czyUsun']==2)
        {
            session_start();
            $lekcja=$_POST['lekcja'];
            $_SESSION['l']=$lekcja;
            header("Location: pdedit.php"); 
        }
    }
    else
    header("Location: zlypas.html");
?>