<?php

session_start();
include 'config/vars.php';
$error = '';

if (isset ($_POST['save']))
{
    $link = mysql_connect($dbserver, $username, $password);
    @mysql_select_db($database) or die( "Unable to select database");

    $check_box = $_POST['moderated'];
    $value = $_POST['value'];
    $uun = $_REQUEST['uun'];

    for($i=0; $i<sizeof($check_box); $i++)
    {
        $line = explode("|",$check_box[$i]);

        $action = $line[0];
        $crowd_id=$line[1];
        $uun=$line[2];

        if ($action == '1' or $action == '-1')
        {
            $get_image_sql = "select image_id from orders.CROWD where id = ".$crowd_id.";";
            $get_image_result =mysql_query($get_image_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $get_image_result . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
            $image_id = mysql_result($get_image_result, 0, 'image_id');

            $vote_insert_sql = "insert into orders.VOTES (crowd_id, submitter, voter, image_id, quality) values (".$crowd_id.", '".$uun."', '".$_SESSION['uun']."','".$image_id."',".$action.");";
            $vote_insert_result=mysql_query($vote_insert_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $vote_insert_result . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
            //echo 'SQL'.$vote_insert_sql;

            $vote_sql = "select sum(quality) as votes from orders.VOTES where id = ".$crowd_id.";";
            $vote_result=mysql_query($vote_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $vote_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
            $votes = mysql_result($vote_result,0, 'votes');


            if ($votes >= 2)
            {
                $status = 'A';

            }
            else if ($votes <= -2)
            {
                $status = 'R';
            }
            else
            {
                 $status = 'M';
            }

            //update the user to value of 'C' (complete) based on the chosen uun
            $sql = "UPDATE orders.CROWD set status = '".$status."'  where id= ".$crowd_id.";";
            $result = mysql_query($sql) or die( "A MySQL error has occurred.<br />Your Query: " . $sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

        }
    }
}
?>


<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <title>Metadata Games</title>
    <?php echo $_SESSION['stylesheet']; ?>
    <meta name="author" content="Library Digital Development" />
    <meta name="description" content=
    "Edinburgh University DIU Crowd Sourcing" />
    <meta name="distribution" content="global" />
    <meta name="resource-type" content="document" />
    <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
</head>

<body>
<?php include_once("./../analyticstracking.php") ?>
<div class = "central">
<div class = "heading">
    <a href="gameMenu.php" title="Metadata Games">
        <img src="<?php echo $_SESSION['banner']; ?>" alt="The University of Edinburgh Image Collections" width="800" height="80" border="0" />
    </a>
    <hr/>
        <h2>HELP US DESCRIBE OUR IMAGES</h2>
    <hr/>
</div>
			<?php

            $link = mysql_connect($dbserver, $username, $password);
            @mysql_select_db($database) or die( "Unable to select database".$database);
            /*
            if (isset ($_POST['moderated']))
            {

                $check_box = $_POST['moderated'];
                $value = $_POST['value'];
                $uun = $_REQUEST['uun'];
                for($i=0; $i<sizeof($check_box); $i++)
                {
                    $line = explode("|",$check_box[$i]);
                    $action = $line[0];
                    $crowd_id=$line[1];

                    if ($action == '1' or $action == '-1')
                    {
                        $vote_sql = "select * from orders.CROWD where id = ".$crowd_id.";";
                        $vote_result=mysql_query($vote_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $vote_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

                        $votes = mysql_result($vote_result,0, 'votes');

                        $votes = $votes++;

                        if ($votes >= 2)
                        {
                            $status = 'A';

                        }
                        else
                        {
                            $status = 'M';
                        }

                        $sql = "UPDATE orders.CROWD set status = '$status'  where id= ".$crowd_id.";";
                        $result=mysql_query($sql) or die( "A MySQL error has occurred.<br />Your Query: " . $sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

                    }
                }

            }
            */

            $mpointssql = "select count(*) as mtotal from CROWD where uun = '".$_SESSION['uun']."' and status = 'M';";
            $mpointsresult=mysql_query($mpointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $mpointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $mpoints = mysql_result($mpointsresult, 0, 'mtotal');

            $vpointssql = "select count(*) as vtotal from VOTES where voter = '".$_SESSION['uun']."';";
            $vpointsresult=mysql_query($vpointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $vpointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $vpoints = mysql_result($vpointsresult, 0, 'vtotal');

            $apointssql = "select count(*) as atotal from CROWD where uun = '".$_SESSION['uun']."' and status = 'A';";
            $apointsresult=mysql_query($apointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $apointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $apoints = mysql_result($apointsresult, 0, 'atotal');

            $ppointssql = "select count(*) as ptotal from CROWD where uun = '".$_SESSION['uun']."' and status = 'P';";
            $ppointsresult=mysql_query($ppointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $ppointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $ppoints = mysql_result($ppointsresult, 0, 'ptotal');

            $upointssql = "select sum(quality) as utotal from VOTES where submitter = '".$_SESSION['uun']."';";
            $upointsresult=mysql_query($upointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $upointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $upoints = mysql_result($upointsresult, 0, 'utotal');

            $pointstotal = $mpoints + $mpoints + $vpoints + $upoints + $apoints + $ppoints;


            $_SESSION['points'] = $pointstotal;

            echo "<h4>Hello " . $_SESSION['first_name'] . ", you currently have <span class='blink'>" . $_SESSION['points'] ."</span> point";

            if ($_SESSION['points'] != 1)
            {
                echo 's';
            }


            echo "!</h4>";
            if ($_SESSION['points'] >= 200)
            {
                echo '<span class="goldstars">*****</span>';
            }
            else if ($_SESSION['points'] >=150)
            {
                echo '<span class="silverstars">***</span>';
            }
            else if ($_SESSION['points'] >= 100)
            {
                echo '<span class="bronzestars">*</span>';
            }

            if ($_SESSION['theme'] == 'art' and $_REQUEST['images'] == 10)
            {
                echo '<table style = "text-align: center;">
                                    <tr>
                                        <td>GAME OVER!</td>
                                    </tr>
                                    <tr>
                                      <td class="menutext" colspan="2">Thanks for doing all that voting. Keep an eye on your scores - there could be a prize for you!</td>
                                    <tr> <td colspan="2"><input type="submit" name = "save" style = "width:500px;" value="Go to voting"/></td></tr>
                      </table>';
            }
            else
            {
                echo '<hr />';

            if ($_SESSION['theme'] == 'art' || $_SESSION['theme'] == 'artAccessible')
                    {
                        $rand_sql = "
                                select
                                i.image_id,
                                i.collection,
                                i.shelfmark,
                                i.title,
                                i.author,
                                i.page_no,
                                i.jpeg_path
                                from
                                orders.IMAGE i
                                join (select x.image_id from orders.IMAGE x, orders.CROWD y where x.image_id = y.image_id and x.collection = 20 and y.status = 'M' and y.uun <> '".$_SESSION['uun']."' and x.image_id not in (select v.image_id from orders.VOTES v where v.voter = '".$_SESSION['uun']."') order by rand() limit 1)
                                as a on i.image_id = a.image_id
                                ;
                                ";
                    }
                    else
                    {
                        $rand_sql = "
                                select
                                i.image_id,
                                i.collection,
                                i.shelfmark,
                                i.title,
                                i.author,
                                i.page_no,
                                i.jpeg_path
                                from
                                orders.IMAGE i
                                join (select x.image_id from orders.IMAGE x, orders.CROWD y where x.image_id = y.image_id and y.status = 'M' and y.uun <> '".$_SESSION['uun']."' and x.image_id not in (select v.image_id from orders.VOTES v where v.voter = '".$_SESSION['uun']."') order by rand() limit 1)
                                as a on i.image_id = a.image_id
                                ;
                                ";
                    }

                    $result=mysql_query($rand_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $rand_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
                    $count = mysql_numrows($result);
                    $images = $_REQUEST['images'];
                    $images++;


                    if ($count == 0)
                    {
                        echo '<div class = "sourcebox">
                        <p>No items to show!</p>
                        </div>';
                    }
                    else
                    {


                        $i = 0;

                        $image_id = mysql_result($result, $i, 'image_id');
                        $collection = mysql_result($result, $i, 'collection');
                        $shelfmark = mysql_result($result, $i, 'shelfmark');
                        $title = mysql_result($result, $i, 'title');
                        $author = mysql_result($result, $i, 'author');
                        $page_no = mysql_result($result, $i, 'page_no');
                        $jpeg_path = mysql_result($result, $i, 'jpeg_path');
                        $publication_status = mysql_result($result, $i, 'publication_status');
                        $size = getimagesize('../'.$jpeg_path);
                        $fullwidth = $size[0];
                        $fullheight = $size[1];

                        if ($fullheight > $fullwidth)
                        {
                            $aspect = $fullheight/ $fullwidth;
                            $short_side = 350 / $aspect;
                            $dimstyle = "height: 95%";
                            $divstyle= "height: 490; width: " . $short_side . " px; vertical-align: middle;";
                        }
                        else
                        {
                            $aspect = $fullwidth / $fullheight;
                            $short_side = 350 / $aspect;
                            $dimstyle = "width: 95%";
                            $divstyle = "height: " . $short_side . " px; width: 550px; vertical-align: middle;";
                        }


                        echo '
                    <div class = "sourcebox">
                        <div class = "plusheading">
                            <h3>+++++++++++++++++++++++++++++++++++++</h3>
                            <h3>+++++++ What Do You Think? +++++++</h3>
                            <h3>+++++++++++++++++++++++++++++++++++++</h3>
                        </div>
                        <div class = "box">
                        </div>
                    </div>
                    <div class="sourcebox">
                        <div class = "heading">
                            <h4>'.$title.'</h4>
                        </div>

                        <div class = "image">
                        ';

                        $urlrecordid = ltrim($image_id, '0');
                        $urlsql = "select
                                    recordid, objectid, imageid, institutionid, collectionid
                                   from
                                        OBJECTIMAGE
                                   where
                                    recordid = ".$urlrecordid.";";
                        $urlresult=mysql_query($urlsql) or die( "A MySQL error has occurred.<br />Your Query: " . $urlsql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
                        $count = mysql_numrows($urlresult);

                        $urlobjectid = mysql_result($urlresult, 0, 'objectid');
                        $urlimageid = mysql_result($urlresult, 0, 'imageid');
                        $urlinstid = mysql_result($urlresult, 0, 'institutionid');
                        $urlcollid = mysql_result($urlresult, 0, 'collectionid');



                        echo '<p><a href= "http://images.is.ed.ac.uk/luna/servlet/detail/'.$urlinstid.'~'.$urlcollid.'~'.$urlcollid.'~'.$urlobjectid.'~'.$urlimageid.'" target = "_blank"><img src = "../'.$jpeg_path.'" style = "'.$divstyle.'"/></a></p>
                        </div>
                    </div>
                        <div class = "info">

                            <div class = "heading">
                                <h3>Pending Information: Image '.$image_id.'</h3>
                            </div>

                        <form action = "gameCrowdSourcingApproval.php?theme=' . $_REQUEST['theme'] . '&images=' . $images . '" method = "post">
                        <div class="box">
                        <p class = "menutext">Select the relevant radio button.</p>
                        <table class ="radio">
                         <tr>
                         <td class="label">Cataloguer</td>
                         <td class="typelabel">Type</td>
                         <td class="label">Value</td>
                         <td class = "radiotd">Good</td>
                         <td class = "radiotd">?</td>
                         <td class = "radiotd">Bad</td>
                         </tr>
                         <tr>
                         <td class="label">----------</td>
                         <td class="typelabel">----</td>
                         <td class="label">-----</td>
                         <td class = "radiotd">---</td>
                         <td class = "radiotd">---</td>
                         <td class = "radiotd">---</td>
                         </tr>';

                $data_sql = "select c.id as crowd_id, u.first_name, u.surname, value_text, c.status, c.type, c.uun from orders.CROWD c, orders.USER u where c.uun = u.uun and c.status = 'M' and image_id = ".$image_id.";";
                $data_result = mysql_query($data_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $data_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
                $data_count = mysql_numrows($data_result);

                $k = 0;
                $crowds = array();
                while ($k <  $data_count)
                {
                    $crowd_id = mysql_result ($data_result, $k, 'crowd_id');
                    $crowds[$k] = $crowd_id;

                    $first_name = mysql_result($data_result, $k, 'first_name');
                    $surname = mysql_result($data_result, $k, 'surname');
                    $value_text = mysql_result($data_result, $k, 'value_text');
                    $type = mysql_result($data_result, $k, 'type');
                    $uun = mysql_result($data_result, $k, 'uun');
                    //<input type="hidden" name = "crowd_id" value = '.$crowd_id[$k].'/>

                    // echo '<tr><td>From '.$first_name .' '.$surname.'</td><td> Value for '.$type.': </td><td><input type = "text" name = "value['.$k.']" value = "'.$value_text.'"</td><td><input type="checkbox" name="moderated['.$k.']" value = "'.$crowd_id.'|'.$uun.'"/></td></tr>';

                    echo '<tr>
                    <td class="label">'.$first_name .' '.$surname.'</td>
                    <td class="typelabel">'.$type.'</td>
                    <td class="label">'.strtoupper($value_text).'</td>
                    <td class="radiotd"><input type="radio" name="moderated['.$k.']" value = "1|'.$crowd_id.'|'.$uun.'"/></td>
                    <td class="radiotd"><input type="radio" name="moderated['.$k.']" value = "O|'.$crowd_id.'|'.$uun.'"/></td>
                    <td class="radiotd"><input type="radio" name="moderated['.$k.']" value = "-1|'.$crowd_id.'|'.$uun.'"/></td>
                    </tr>';
                    $k++;

                }

                //$crowd_serialized = serialize($crowds);

                echo '
                </table>
                </div><br><input type="submit" name = "save" style = "width:520px;" value="Submit votes and get new image" />
                </form>';

             }
        }

			?>
</div>
<?php include 'footer.php';?>
		</div> <!-- div central -->
	</body>
</html>
