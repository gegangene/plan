<?php
date_default_timezone_set("Europe/Warsaw");

//tryb kolorystyczny
if(!isset($_COOKIE['colour']))
{
    setcookie("colour","dark",time()+86400*14,"./");
    $cc="dark";
}
else
{
    $cc=$_COOKIE['colour'];
}
if(isset($_GET['v']))
{
    switch($_GET['v'])
    {
        case "l":
            setcookie("colour","light",time()+86400*14,"./");
            break;
        case "d":
            setcookie("colour","dark",time()+86400*14,"./");
            break;
        case "m":
            setcookie("colour","magenta",time()+86400*14,"./");
            break;
        default:
            setcookie("colour","dark",time()+86400*14,"./");
            break;            
    }
    unset($_GET['v']);
    header("Location: ./");
}
?>
<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link type="text/css" href="style.css?<?=time()?>" rel="stylesheet">
        <link type="text/css" href="ggngnn.css?<?=time()?>" rel="stylesheet">
        <link media="(max-width: 830px)" type="text/css" href="style_m.css?<?=time()?>" rel="stylesheet">
        <link rel="stylesheet" media="(min-width: 830px)" href="style_d.css?<?=time()?>" type="text/css">
        <link rel="shortcut icon" href="https://pbp.one.pl/informatyka/3_LO/zdj/logos.png">
        <title>Plan lekcji klasy III Bg na rok szkolny 2021/22</title>

        <?php
			$urzadzenie="";
			$upom=$_SERVER['HTTP_USER_AGENT'];
			$jstype="";
			for($i=24; $i<40; $i++)
				$urzadzenie.=$upom[$i];
			//echo $urzadzenie;//."\t".$upom;
			if($urzadzenie!=" iPhone OS 9_3_6")
				$jstype="zmiana";
			else
				$jstype="izmiana936";

            $przed=false;
            $po=false;
            $mess="";
            //ujednolicenie czasu
            function czasoDostosowywarka($godzina, $minuty)
            {
                return $godzina*60+$minuty;
            }

            $aktualnyCzas=czasoDostosowywarka(date("H"),date("i"));
            $pogrubienie=0;
            
            function ujednolicarka($dt)
            {
                if($dt>7)
                    $dt=$dt%7;
                if($dt<1)
                    $dt=7-($dt*(-1)%7);
                return $dt;
            }

            function wyznaczDzienTygodniaInt($podany)
            {
                switch ($podany)
                {
                    case "Mon":
                        return 1;
                        break;
                    case "Tue":
                        return 2;
                        break;
                    case "Wed":
                        return 3;
                        break;
                    case "Thu":
                        return 4;
                        break;
                    case "Fri":
                        return 5;
                        break;
                    case "Sat":
                        return 6;
                        break;
                    case "Sun":
                        return 7;
                        break;
                    default:
                        return 0;
                        break;
                }
            }
            function wyznaczDzienTygodniaStr($podany)
            {
                switch ($podany)
                {
                    case 1:
                        return "Poniedziałek";
                        break;
                    case 2:
                        return "Wtorek";
                        break;
                    case 3:
                        return "Środa";
                        break;
                    case 4:
                        return "Czwartek";
                        break;
                    case 5:
                        return "Piątek";
                        break;
                    case 6:
                        return "Sobota";
                        break;
                    case 7:
                        return "Niedziela";
                        break;
                    default:
                        return 'błąd w wyznaczaniu dnia tygodnia! skontaktuj się z administratorem poprzez mail <a href=""mailto:ggngnn@pbp.one.pl">ggngnn@pbp.one.pl</a>';
                        break;
                }
            }

            function nastoCheck($numerkowo)
            {
                if(($numerkowo-$numerkowo%10)==10)
                    return 100;
                else if(($numerkowo%10-$numerkowo%100)==10)
                    return 100;
                else
                    return $numerkowo;
            }

            function literkowo($numerek)
            {
                $odp="";
                $numerek=nastoCheck($numerek);
                if((($numerek%10)<5)&&(($numerek%10)>1))
                    $odp="y";
                else if($numerek>4)
                    $odp="o";
                else
                    $odp="a";
                return $odp;
            }
		    $mmonth=date("m");
            //echo $mmonth;
            $weekday=wyznaczDzienTygodniaInt(date('D'));
            if(isset($_GET['zmiana']))
            {
                if($weekday==$_GET['zmiana'])
                {
                    unset($_GET['zmiana']);
                    header("Location: ./");
                }
                $weekday=$_GET['zmiana'];
                unset($_GET['zmiana']);
            }
            $dztyg=wyznaczDzienTygodniaStr($weekday);

            $przedmiot=array(array(),array(),array());
            $sala=array(array(),array(),array());
            $grupa=array(array(),array(),array());
            for($a=0; $a<8; $a++)
            {
                for($b=0; $b<3; $b++)
                {
                    $przedmiot[$b][$a]="";
                    $sala[$b][$a]="";
                    $grupa[$b][$a]="";
                }
            }
            $lessons=array(0,0,0);
            for($it=$weekday-1; $it<$weekday+2; $it++)
            {
                $pom=$it-$weekday+1;
                $weekdayin=$it;
                $weekdayin=ujednolicarka($weekdayin);
                //echo $weekdayin."|";
                if($mmonth=='07'||$mmonth=='08'||(($weekdayin>5)&&($weekday<8)))
                   {$weekdayin=0;}
                
                //echo $weekdayin."|";
                //otwieranie pliku
                $source = fopen("./sources/$weekdayin.txt", "r") or die("plik lekcji ".$weekdayin.".txt nie istnieje bądź jest uszkodzony");

                $arrcount=0;
                $lessons[$pom]=0;

                //odczytywanie zawartości pliku linijka po linijce i analiza
                while(!feof($source))
                {
                    $lekcyja = fgets($source);
                    $i=intval($lekcyja[0]);
                    $lessons[$pom]+=$i;
                    for($n1=0; $n1<$i; $n1++)
                    {	$n=1;
                        while($lekcyja[$n]!=';')
                        {
                            $przedmiot[$pom][$arrcount] .= $lekcyja[$n];
                            $n++;
                        }
                        $n++;
                        while($lekcyja[$n]!='.')
                        {
                            $sala[$pom][$arrcount] .= $lekcyja[$n];
                            $n++;
                        }
                        $n++;
                        while($lekcyja[$n]!="\\")
                        {
                            $grupa[$pom][$arrcount] .= $lekcyja[$n];
                            $n++;
                        }
                    $arrcount++;
                    }
                }
                fclose($source);
            }
            //zdobywanie lekcji i pd dla kolejnego dnia, jeżeli kolejne dni to weekend
            
            if($weekdayin==0)
            {
                $przedmiotponiedzialek=array("","","","","","","","");
                $lessonsponiedzialek=0;
                $sourceponiedzialek=fopen("./sources/1.txt", "r") or die ("plik lekcji dla poniedziałku nie istnieje bądź jest uszkodzony");
                $arrcount=0;
                $lessonsponiedzialek=0;

                //odczytywanie zawartości pliku linijka po linijce i analiza
                while(!feof($sourceponiedzialek))
                {
                    $lekcyja = fgets($sourceponiedzialek);
                    $i=intval($lekcyja[0]);
                    $lessonsponiedzialek+=$i;
                    for($n1=0; $n1<$i; $n1++)
                    {	$n=1;
                        while($lekcyja[$n]!=';')
                        {
                            $przedmiotponiedzialek[$arrcount] .= $lekcyja[$n];
                            $n++;
                        }
                        $n++;
                        $arrcount++;
                    }
                }
                fclose($sourceponiedzialek);
            }

            //która godzina - otwieranie pliku
            $source0 = fopen("./sources/godziny.txt", "r") or die("plik z godzinami nie istnieje bądź jest uszkodzony");

                //odczytywanie zawartości pliku
                $pomoc=1;
                $godz1 = intval(fgets($source0));
                $min1 = intval(fgets($source0));
                //echo $godz1."|".$min1."<br/>";
                //czy lekcje już trwają
                if((czasoDostosowywarka($godz1,$min1)>$aktualnyCzas)&&$weekday<6&&$mmonth!='07'&&$mmonth!='08')
                {
                    $przed=true;
                    $iledokonca=czasoDostosowywarka($godz1,$min1)-$aktualnyCzas;
                    $mess="do pierwszej lekcji został".literkowo($iledokonca)." ".$iledokonca." min.";
                }
                while($pomoc<=$lessons[1]&&$weekday<6)
                {
                    $godz2 = intval(fgets($source0));
                    $min2 = intval(fgets($source0));
                    $godz3 = intval(fgets($source0));
                    $min3 = intval(fgets($source0));
                    //ostateczne sprawdzanie godziny
                    if((czasoDostosowywarka($godz1,$min1)<=$aktualnyCzas)&&(czasoDostosowywarka($godz2,$min2)>$aktualnyCzas))
                    {
                        $pogrubienie=$pomoc;
                        $iledokonca=czasoDostosowywarka($godz2,$min2)-$aktualnyCzas;
                        $mess="do końca lekcji został".literkowo($iledokonca)." ".$iledokonca." min.";
                    }
                    if((czasoDostosowywarka($godz2,$min2)<=$aktualnyCzas)&&(czasoDostosowywarka($godz3,$min3)>$aktualnyCzas))
                    {
                        $pogrubienie=$pomoc;
                        $iledokonca=czasoDostosowywarka($godz3,$min3)-$aktualnyCzas;
                        $mess="do końca przerwy został".literkowo($iledokonca)." ".$iledokonca." min.";
                    }
                    $pomoc++;
                    $godz1 = $godz3;
                    $min1 = $min3;
                }
                fclose($source0);
                //czy koniec lekcji
                //echo $godz1."|".$min1."||".$lessons[1];
                if((czasoDostosowywarka($godz1,$min1)<$aktualnyCzas)&&$weekday<6&&$mmonth!='07'&&$mmonth!='08')
                    $po=true;
                if($weekday>5)
                    $pogrubienie=1;
                unset($godz1);
                unset($min1);
                unset($pomoc);
				
            //prace domowe - tworzenie tabeli
            $hmwrk=array(array("","","","","","","",""),array("","","","","","","",""));
			//prace domowe - otwieranie pliku
            $source1 = fopen("./sources/homework.txt", "r") or die("plik z pracami domowymi nie istnieje bądź jest uszkodzony");

                //odczytywanie zawartości pliku
                while(!feof($source1))
                {
					$lekcja0 = fgets($source1);
                    $lekcja = "";
                    for($nn=0; $nn<strlen($lekcja0); $nn++)
                    {
                        if($lekcja0[$nn]==";")
                            break;
                        else
                            $lekcja=$lekcja.$lekcja0[$nn];
                    }
					$homework = fgets($source1);
                    for($n=0; $n<$lessons[1]; $n++)
                    {
                        $urok=$przedmiot[1][$n];
                        //$urok=$przedmiot[1][$n].$lekcja[strlen($lekcja)-1];
                        if((strtoupper($urok))==(strtoupper($lekcja)))
                        {
                            //echo "abacaba";
                            $hmwrk[0][$n]=$homework;
                        }
                        //else
                        //echo strtoupper($lekcja).strtoupper($urok."|");
                    }
                    if($weekdayin>0)
                    {
                        for($n=0; $n<$lessons[2]; $n++)
                        {
                            $urok=$przedmiot[2][$n];
                            if((strtoupper($urok))==(strtoupper($lekcja)))
                            {
                                $hmwrk[1][$n]=$homework;
                            }
                        }
                    }
                    else
                    {
                        for($n=0; $n<$lessonsponiedzialek; $n++)
                        {
                            $urok=$przedmiotponiedzialek[$n];
                            if((strtoupper($urok))==(strtoupper($lekcja)))
                            {
                                $hmwrk[1][$n]=$homework;
                            }
                        }
                    }
                }
                fclose($source1);
        ?>
        <link type="text/css" href="<?=$cc?>.css?<?=time()?>" rel="stylesheet">
    </head>

    <body>
        <header>
            <h1>Plan lekcji na rok 2021/22:</h1>
        </header>
        <div class="s50 inline">
            <aside class="padd1p max-content bckgrnd"><?= 'aktualna data i czas: <div class="whiteDiv"><span class="colourlyReversedText">'.date("d.m.Y")." (".wyznaczDzienTygodniaStr(wyznaczDzienTygodniaInt(date("D")))."), ".date('H:i')."</span></div>"?></aside>
        </div>
        
        <div class="s50 inline rightText">
            <nav class="inline padd05p max-content bckgrnd">
            <a href="?v=m" title="przełącz wygląd strony na: kolorowy"><img src="icons/kolorowy.svg" alt="ikona: kolorowy wygląd strony"></a> 
            <a href="?v=d" title="przełącz wygląd strony na: ciemny"><img src="icons/czarny.svg" alt="ikona: ciemny wygląd strony"></a> 
            <a href="?v=l" title="przełącz wygląd strony na: jasny"><img src="icons/bialy.svg" alt="ikona: jasny wygląd strony"></a>
            </nav>
        </div>
        <div class="cleaner"></div>
        
        <nav class="onlyMobile">
            <div class="s50 inline">
				<a href="./?zmiana=<?=ujednolicarka($weekday-1)?>">
					<div class="button centerText" id="yesteButton">
						wczoraj
					</div>
				</a>
            </div>
            <div class="s50 inline">
				<a href="./?zmiana=<?=ujednolicarka($weekday+1)?>">
					<div class="button rightDiv centerText" id="tommoButton">
						jutro
					</div>
				</a>
            </div>
        </nav>
        <div>
            <div class="onlyDesktop mainDivMobile floatL trujca" id="box0">
                <h3 style="margin:0 0 2% 0" class="centerText"><?=wyznaczDzienTygodniaStr(ujednolicarka($weekday-1))?>:</h3>
                <div>
                    <div class="inline zbiorprzedm">
                        <?php
                            for($n=0; $n<$lessons[0]; $n++)
                            {
                                echo '<div class="przedmiot LargeText';
                                if($grupa[0][$n]>1)
                                    echo ' innagrupa';
                                echo '">'.$przedmiot[0][$n].'</div>';
                            }
                        ?>
                    </div>
                    <div class="inline rightText wyzejKurna">
                        <div class="inline centerText">
                            sala:
                            <?php
                                for($n=0; $n<$lessons[0]; $n++)
                                {
                                    echo '<div class="';
                                    if($grupa[0][$n]>1)
                                        echo 'innagrupa';
                                    echo '">'.$sala[0][$n].'</div>';
                                }
                            ?>  
                        </div> 
                    </div>
                </div>
            </div>
            <div class="floatL mobileBlock mainDivMobile trujca" id="box1">
                <a href="#" class="noDecoration" id="dnioZmieniacz"><h3 style="margin:0 0 2% 0" class="centerText"><?=wyznaczDzienTygodniaStr(ujednolicarka($weekday))?>:</h3></a>
                <div class="centerText znikniete" id="dnioZmieniaczowaLista">
                    <?php
                        for($n=$weekday+1; $n<$weekday+7; $n++)
                        {
                            if($n%7!=0&&ujednolicarka($n%7)!=wyznaczDzienTygodniaInt(date("D")))
                            {
                                $k=$n%7;
                                echo '<a href="?zmiana='.$k.'">'.wyznaczDzienTygodniaStr(ujednolicarka($n%7))."</a><br>";
                                unset($k);
                            }
                            else if(ujednolicarka($n)!=wyznaczDzienTygodniaInt(date("D")))
                            {
                                echo '<a href="?zmiana='.$n.'">'.wyznaczDzienTygodniaStr(ujednolicarka($n))."</a><br>";
                            }
                            else
                                echo '<a href="./">'.wyznaczDzienTygodniaStr(ujednolicarka($n))."</a><br>";
                        }
                    ?>
                </div>
                <div>
                    <div class="inline zbiorprzedm">
                        <?php
                            if($przed)
                            {
                                echo '<div class="przedmiot boldText wyroznienie1 LargeText">lekcje jeszcze nie rozpoczęte.</div>';
                                echo '<div style="font-size:15px;" class="przedmiot wyroznienie1">'.$mess.'</div>';
                            }
                            for($n=0; $n<$lessons[1]; $n++)
                            {
                                echo '<div class="przedmiot ';
                                if($n+1==$pogrubienie)
                                    echo 'boldText wyroznienie1';
                                if($grupa[1][$n]>1)
                                    echo 'innagrupa';
                                echo ' LargeText">';
                                //if($weekday<6)
                                    if($hmwrk[0][$n]!="")
                                        echo '<a href="#" class="noDecoration pd" onclick="lekcjoZmieniacz('.$n.')" id="lekcjoZmieniacz'.$n.'">'; 
                                echo $przedmiot[1][$n];
                                //if($weekday<6)
                                    if($hmwrk[0][$n]!="")
                                        echo '</a>'; 
                                echo '</div>';
                                if(($hmwrk[0][$n]!="")&&($weekday<6)&&$mmonth!='07'&&$mmonth!='08')
                                	echo '<div style="font-size:15px;" class="przedmiot wyroznienie1a znikniete" id="lekcjoZmieniaczowaLista'.$n.'">'.$hmwrk[0][$n].'</div>';
                                if($n+1==$pogrubienie&&$weekday<6&&$mmonth!='07'&&$mmonth!='08')
                                    echo '<div style="font-size:15px;" class="przedmiot wyroznienie1">'.$mess.'</div>';
                            }
                            if($po)
                            {
                                echo '<div class="przedmiot boldText wyroznienie1 LargeText">lekcje zakończone na dziś.</div>';
                            }
                        ?>
                    </div>
                    <div class="inline rightText wyzejKurna">
                        <div class="inline centerText">
                            sala:
                            <?php
                                if($przed)
                                {
                                    echo '<div class="boldText wyroznienie2"><br>ALERT</div>';
                                }
                                for($n=0; $n<$lessons[1]; $n++)
                                {
                                    echo '<div class="';
                                    if($n+1==$pogrubienie)
                                        echo 'boldText wyroznienie2';
                                    if($grupa[1][$n]>1)
                                        echo 'innagrupa';
                                    echo '">'.$sala[1][$n].'</div>';
                                    if($n+1==$pogrubienie&&$weekday<6)
                                        echo '<div class="LargeText"><br></div>';
                                    echo '<div class="znikniete" id="lekcjoZmieniaczowaListaSala'.$n.'"><br/></div>';
                                }
                                if($po)
                                {
                                    echo '<div class="boldText wyroznienie2">ALERT</div>';
                                }
                            ?>  
                        </div> 
                    </div>
                </div>
            </div>
            <div class="onlyDesktop floatL mainDivMobile trujca" id="box2">
                <h3 style="margin:0 0 2% 0" class="centerText"><?=wyznaczDzienTygodniaStr(ujednolicarka($weekday+1))?>:</h3>
                <div>
                    <div class="inline zbiorprzedm">
                        <?php
                            for($n=0; $n<$lessons[2]; $n++)
                            {
                                echo '<div class="przedmiot LargeText';
                                if($grupa[2][$n]>1)
                                    echo ' innagrupa';
                                echo '">'.$przedmiot[2][$n].'</div>';
                            }
                        ?>
                    </div>
                    <div class="inline rightText wyzejKurna">
                        <div class="inline centerText">
                            sala:
                            <?php
                                for($n=0; $n<$lessons[2]; $n++)
                                {
                                    echo '<div class="';
                                    if($grupa[2][$n]>1)
                                        echo 'innagrupa';
                                    echo'">'.$sala[2][$n].'</div>';
                                }
                            ?>  
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <div class="cleaner"></div>
        <?php
        /*
        <h2>Nadchodzące klęski:</h2>
        <h3>11.10.2021 r. — Sprawdzian z funkcji wymiernej i logarytmicznej</h3>
        <h3>12.10.2021 r. — Sprawdzian ze słownictwa z działu Rodzina</h3>
        <h3>13.10.2021 r. — «Преступление и наказание» Фёдора Достоевского</h3>
        */
        ?>
        <div class="centerText">
        <h3 id="pdpokaz"><a href="#">Praca domowa na kolejny dzień:</a></h3>
        <div class="znikniete block" id="pdnajutro">    
        <?php
            $ilepdnajutro=0;
            if($weekdayin>0)
            {
                echo "<h4>".wyznaczDzienTygodniaStr($weekdayin).":</h4>";
                for($n=0; $n<$lessons[2]; $n++)
                {
                    $niebylo=true;
                    for($i=0; $i<$n; $i++)
                    {
                        if($przedmiot[2][$n]==$przedmiot[2][$i])
                        $niebylo=false;
                    }
                    if($hmwrk[1][$n]!=""&&$niebylo)
                    {
                        echo $przedmiot[2][$n]." — ".$hmwrk[1][$n]."<br/>";
                        $ilepdnajutro++;
                    }
                }
            }
            else
            {
                echo "<h4>Poniedziałek:</h4>";
                for($n=0; $n<$lessonsponiedzialek; $n++)
                {
                    $niebylo=true;
                    for($i=0; $i<$n; $i++)
                    {
                        if($przedmiotponiedzialek[$n]==$przedmiotponiedzialek[$i])
                        $niebylo=false;
                    }
                    if($hmwrk[1][$n]!=""&&$niebylo)
                    {
                        echo $przedmiotponiedzialek[$n]." — ".$hmwrk[1][$n]."<br/>";
                        $ilepdnajutro++;
                    }
                }
            }
            if($ilepdnajutro==0)
            {
                echo "na jutro nie przewidziano żadnej pracy domowej :)";
            }
            ?>
        </div>
        </div>
        <aside>
            <p class="centerText"><a href="pd.html">Dodaj/zmień prace domowe</a></p>
        </aside>
        <p class="nieaktyw centerText">pbp://plan v. beta 0.1.7 b (18-12-2022)</p>
        <p class="znikniete centerText nieaktyw">co nowego: wczoraj/jutro usprawnione dla debili, którzy nie umieli używać listy.\n b:domyslny motyw to czarny, poprawa zmiennyh motywu</p>
			<script src="<?=$jstype?>.js?<?=time()?>"></script>
        <span class="nieaktyw">Do dodania:
        <aside>
            <ol>
                <li><span style="text-decoration: line-through">pokazywanie pracy domowej na kolejny dzień</span></li>
                <li>moduł zastępstw</li>
                <li><span style="text-decoration: line-through">prostszy system dodawania pracy domowej niż edycja txt przez ftp</span></li>
                <li>dodanie możliwości sprawdzenia dzwonków w inny sposób niż czytając suchy plik txt</li>
                <li><span style="text-decoration: line-through">sprawdzenie co nie c'mon z tymi homeworkami (angielski wa wtornik i pierwsza lekcja w pliku)</span></li>
            </ol>
        Wyszukał większość błędów: insp. Piotr Maciejak (IIILO PTb)</span>
        <br/><p>Adres do zgłaszania błędów/pomocy technicznej (godz. pracy: 8:00-20:00 CEST): <a href="mailto:ggngnn@pbp.one.pl">ggngnn@pbp.one.pl</a></p>
        </aside>
    </body>
</html>
