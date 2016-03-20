<?php
	session_start();
	$user = $_SESSION['username'];
  	include_once("connectdb.php");
  	/*require('src/autoload.php');*/
?>
<?php
	Class RECT{
		public $x, $y, $rx, $by;
		public function RECT($ix, $iy, $i_w, $i_h){
			if( is_numeric($ix) && is_numeric($iy) && is_numeric($i_w) && is_numeric($i_h) ){
				$this->x = $ix;
				$this->y = $iy;
				$this->rx = $this->x+$i_w;
				$this->by = $this->y+$i_h;
			}else{
				exit();
			}
		}
	}
	$data = json_decode($_POST['data']);
	if($data){
		if( !($data->cilent_info || $data->cilent_data) ){
			exit();
		}else{
			if( !($data->cilent_info->x || $data->cilent_info->y || $data->cilent_info->width || $data->cilent_info->height) &&
				!($data->cilent_data->inserted || $data->cilent_data->changed || $data->cilent_data->deleted) ){
				exit();
			}else{
				/* real process step */
				$result = array();
				$cilent = new RECT($data->cilent_info->x, $data->cilent_info->y, $data->cilent_info->width, $data->cilent_info->height);
				foreach($data->cilent_data->inserted as $i){
					$x = addslashes($i->x);
					$y = addslashes($i->y);
					$title = addslashes($i->title);
					$content = addslashes($i->content);
					@$sql = "INSERT INTO paper (x, y, title, content, userid) VALUES('".$x."','".$y."','".$title."','".$content."',(SELECT id FROM user WHERE username = '".$user."'));";
					$res = mysqli_query($con, $sql) or die(mysqli_error($con));
				}
				foreach($data->cilent_data->changed as $i){
					$id = addslashes($i->id);
					$x = addslashes($i->x);
					$y = addslashes($i->y);
					$title = addslashes($i->title);
					$content = addslashes($i->content);
					@$sql = "UPDATE paper SET x = '".$x."', y = '".$y."', title = '".$title."', content = '".$content."', userid = (SELECT id FROM user WHERE username = '".$user."') WHERE postid = '".$id."';";
					$res = mysqli_query($con, $sql) or die(mysqli_error($con));
				}
				foreach($data->cilent_data->deleted as $i){
					$id = addslashes($i);
					@$sql = "DELETE FROM paper WHERE postid = '".$id."';";
					$res = mysqli_query($con, $sql) or die(mysqli_error($con));
				}

				@$sql = "SELECT * FROM paper WHERE (x BETWEEN ".$cilent->x." AND ". $cilent->rx .") AND ( y BETWEEN ".$cilent->y." AND ".$cilent->by.")";
				$res = mysqli_query($con, $sql) or die(mysqli_error($con));
				if(mysqli_num_rows($res) >= 1){
					while($rows = mysqli_fetch_assoc($res)){
						array_push($result, array(
								"id"		=>	$rows['postid'],
								"x"			=>	$rows['x'],
								"y"			=>	$rows['y'],
								"title"		=>	$rows['title'],
								"content"	=>	$rows['content']
							));
					}
					echo json_encode($result);
				}else{
					echo "No RESULT";
				}
			}
		}
	}

?>