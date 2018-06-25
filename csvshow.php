<html>
<head>
<style type="text/css">
.r_even {
	background-color: #89DEEF;
}
.square_btn {
    position: relative;
    display: inline-block;
    font-weight: bold;
    padding: 0.5em 1em;
    text-decoration: none;
    border-left: solid 4px #668ad8;
    border-right: solid 4px #668ad8;
    color: #668ad8;
    text-shadow: 0 0 5px white;
    padding: 0.5em 1em;
    background: -webkit-repeating-linear-gradient(-45deg, #cce7ff, #cce7ff 3px,#e9f4ff 3px, #e9f4ff 7px);
    background: repeating-linear-gradient(-45deg, #cce7ff, #cce7ff 3px,#e9f4ff 3px, #e9f4ff 7px);
    transition: .4s;
}
.square_btn:hover {
    background: -webkit-repeating-linear-gradient(-45deg, #cce7ff, #cce7ff 5px,#e9f4ff 5px, #e9f4ff 9px);
    background: repeating-linear-gradient(-45deg, #cce7ff, #cce7ff 5px,#e9f4ff 5px, #e9f4ff 9px);
}
</style>
<script type="text/javascript">
function nextView(){
  var sts=document.getElementById("next").textContent;
  sts=parseInt(sts)+1;
  stsmax=parseInt(sts)+30;
  var h=0;
  for(var k=sts; k<stsmax; k++){
    document.getElementById(k).style.display="";
  }
  document.getElementById("next").textContent=k-1;
}
</script>
</head>
</body>
<?php
getPref();
function getPref(){
  $file = "list_pref.csv";
  $prefFile = fopen($file, "r");
  echo "<form action='blshow.php' method='post'>";
  echo "<div style='text-align:center;'>";
  $prefname = htmlspecialchars($_POST['prefselect']);
  echo "<select name='prefselect' onChange='rePref'>";
  if($prefFile){
    while ($line = fgets($prefFile)) {
      $line = htmlspecialchars($line);
      $prefList='<option value='.$line;
      $prefList=$prefList.'>'.$line.'</option>';
      echo $prefList;
    }
  }
  echo "</select>";
  echo "<p><input type='submit' class='square_btn' value='検索する'>";
  echo "</form><p>";
  if ($prefname == ""){
    $prefname = "北海道";    
  }
  echo $prefname."のデータを表示します。";
  echo "</div>";
  showLists($prefname);
}
function showLists($pref){
  $file = "list_master.csv";
   if ( ( $handle = fopen ( $file, "r" ) ) !== FALSE ) {
      echo "<table border='1' width='100%' style='table-layout: auto;'>";
      echo "<thead>";
      echo "<tr style='background-color: #ABCECE'><th>Company</th><th>Address</th></tr>";
      echo "</thead>";
      echo "</tbody>";
      echo "<tr>";    
      $i=1;
      $i_visible=1;
      $v_flg=0;
      while($line=fgetcsv($handle)){
        if ($i % 2) {
          $tr="<tr id='".$i."' class='r_even'";
        } else {
          $tr="<tr id='".$i."' class='r_odd'";
        }
        if ($i >= 31 ){
          $tr=$tr." style='display:none;'>";
          $v_flg=0;    
        }else{
          $tr=$tr.">";
          $v_flg=1;
        }
        $snake=$tr;
        foreach ($line as $key => $value){
          $td="<td>";
          $tde="</td>";
          $linemb=mb_convert_encoding($value,"utf-8","sjis");
          $snake = $snake.$td.$linemb.$tdl;
          if($key == 1){
            $addrData=$linemb;
          }elseif($key == 0){
            $nameData=$linemb;
          }
        }
        $snake=$snake.":".$i_visible."</tr>";
        if(preg_match("/$pref/",$addrData)){
          print ($snake);
          $i++;
          if($v_flg==1){
            $i_visible++;
          }
        }
      }
      echo "</tbody>";      
      echo "</table>\n";
      fclose ( $handle );
      $i_visible--;
  }
  echo "<div style='text-align:center;'><p>";
  echo "<input id='btn_n' type='button' class='square_btn' value='続きを表示する' onclick='nextView()'>";
  echo "</div>";
  echo "<div id='next' style='display:none'>".$i_visible."</div>";
}
?>
</body>
</html>
