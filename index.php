<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='index.css'/>
</head>
<body>
    <?php 
            //Links external php array files.
            include 'namnsdagar.php';
            include 'monthimg.php';

            //Declares variables
            $day         = date('d');
            $year        = date('Y');
            $week        = date('w');
            $month       = date('n');
            $dayname     = date('D');
            $monthname   = date('F');
            $currentdate = strtotime(date('Y-m-d'));
            $getdate     = $_GET["date"] ?? date('Y-m-d');
            $totaldays   = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            if(empty($getdate)){ $getdate = date("Y-m-01"); }

            $now  = date($getdate);    
            $str2 = strtotime($now);

            //Advances current month forward or backward
            $prevmonth= date("Y-m-01",strtotime("-1 month",$str2));
            $nextmonth= date("Y-m-01",strtotime("+1 month",$str2));

            //changes variables to new month
            $year = date('Y', strtotime("+0 month", $str2));
            $month = date('m', strtotime("+0 month", $str2));
            $monthname = date('F', strtotime("+0 month",$str2));
            $totaldays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        //Creates html elements
        echo "<div id='cont'>";
        echo "<h1> Kalender </h1>";
        echo "<h2>" . $monthname . "</h2>";
        echo "<h3>" . $totaldays . " days" . "</h3>";
        echo "<div id='calendar'>";
        echo "<ul id='listcont'>";

                //Loop that creates each days card.
                for($i = 1; $i <= $totaldays; $i++){

                    $str  = strtotime(date("Y-m-d"));
                    $date = date( $year . '-' . $month .'-' . $i);

                    //Updates and creates new variables
                    $namedate  = date('D', strtotime($date));
                    $numdate   = date('d', strtotime($date));
                    $weekdate  = date('W', strtotime($date));
                    $daynumber = date('z', strtotime($date));
                    $isLeap    = date('L', strtotime($date));
                    $month2    = date('n', strtotime($now));
                    $year2     = date('Y', strtotime($now));
                    $day2      = $i;
                    $file      = fopen("birthday.txt", "r");


                    //Checks for leap year and executes code if it currently is in a leap year
                    if($isLeap == 1){ $dateofyear = date('z', mktime(0,0,0,$month2, $day2, $year2)); }

                    //Executes code if it is not a leap year
                    else{ $dateofyear = date('z', mktime(0,0,0,$month2, $day2, $year2))+1; }

                    //Fetches name day corresponding to the current day number.
                    $namn = implode(" ", $namnsdagar["$dateofyear"]);


                    if($i <10){
                        $dagPlusNoll = "0$i";
                    }
                    else if($i >=10){
                        $dagPlusNoll= $i;
                    }

                    if($bdayArr = fgets($file))
                    {
                    $temp = explode(",", $bdayArr);
                    for($x = 0; $x < count($temp); $x++)
                    {
                        $t = "$month2-$dagPlusNoll";
                        $temp2 = explode(".", $temp[$x]);
                        $temp3 = substr($temp2[0], 5);
                        $bDate = $temp3;
                        if($bDate == $t){
                            $bday.="$temp2[1]";
                            break;
                        }
            
                        else if($bDate != $t){
                             $bday= "";
                         }
                    }
                    }





                    //Creates element with blue background for the current day.
                    if($date == date('Y-m-d')){
                        echo "<li style='background-color:lightblue;'>" . " " . $monthname . " " . $numdate . " " . $namedate  . "<br>" . " W:" . $weekdate . " D:" . $dateofyear . "<br>" . $namn . "<br>" . "Bday: " . $bday .  "</li>";
                    }

                    //Creates element with blue background for the current day and adds week number if it is a monday.
                    else if($date == date('Y-m-d') && $namedate == "Mon"){
                        echo "<li style='background-color:lightblue;'>" . " " . $monthname . " " . $numdate . " " . $namedate  . "<br>" . " D:" . $dateofyear . "<br>" . $namn . "<br>" . "Bday: ". $bday .   "</li>";
                    }

                    //Creates element with week number shown on mondays.
                    else if($namedate == "Mon"){
                        echo "<li>" . " " . $monthname . " " . $numdate . " " . $namedate . "<br>" . " W:" . $weekdate . " D:" . $dateofyear . "<br>" . $namn . "<br>" . "Bday: " . $bday .   "</li>";
                    }

                    //Creates element with red text shown on sundays.
                    else if($namedate == "Sun"){
                        echo "<li style='color:red;' class='break'>" . " " . $monthname . " " . $numdate . " " . $namedate  . "<br>" . " D:" . $dateofyear . "<br>" . $namn . "<br>" . "Bday: " . $bday .   "</li> <br>";
                    }
                    //Creates the remaining elements with no special attribute
                    else{
                        echo "<li>" . " " . $monthname . " " . $numdate . " " . $namedate . "<br>" . " D:" . $dateofyear . "<br>" . $namn . "<br>" . "Bday: " . $bday .  "<br>" . "</li>";
                    }

                    //Changes image based on the month the user is viewing
                    $posterimg = implode("", $monthimg["$month2"]);
                    ?> <style> ul{ background-image: url(<?php echo $posterimg;?>); } </style> <?
                } 

        //Creates html elements
        echo "</ul>";
        echo "<div class='btndiv'>";
    ?>
            <!-- Creates html links that change current date-->
            <a href=" <? echo '?date=' . $prevmonth?> " class='button'>Prev</a>
            <a href=" <? echo '?date=' . $nextmonth?> " class='button'>Next</a>
    <?
        //Creates html elements
        echo "</div>";
        echo "</div>";
    ?>
        <!-- Form that recieves brithday and name -->
        <form action="#" method="POST">
            <label for="bday">Bday:</label>
            <input type="date" name="bDay">
            <br>
            <label for="name">Name:    </label>
            <input name="name" type="text" id="txtName" placeholder="E.g. Bill">
            <br>
            <label for="name">Post:</label>
            <input name="save" type="submit">
        </form>
    <?

        if(isset($_POST["save"])){
            $file=fopen("birthday.txt", "a+");
            $writeB=",". $_POST["bDay"].".".$_POST["name"];
            fwrite($file, $writeB);
        }
        fclose($file);

        echo "</div>";
              
    ?>

    <script src="exempel.js"></script>
</body>
</html>