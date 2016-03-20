<?php
	session_start();
	if(empty($_SESSION['username'])){
		$_SESSION['username'] = "GUEST";
	}
	$user= $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="icon" href="favicon.ico" type="image/x-icon">  
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
	<link rel="stylesheet" type="text/css" href="css/design.css">
	<script type="text/javascript" src="js/jquery-2.1.4.js"></script>
	<!--<script type="text/javascript" src="js/touch2mouse.js"></script>-->
	<script>
		var MemoArray = new Array();
		var MemoBuffer = new Array();
		var ChangeBuffer = new Array();
		var DeleteBuffer = new Array();
		var StackSort = new Array();
		var distance_x = 0;
		var distance_y = 0;
		var cilentWidth = 0;
		var cilentHeight = 0;
		var Area_x = 0;
		var Area_y = 0;
		var Big_Boss = {
			"inserted" : [],
			"changed" : [],
			"deleted" : []
		};
		function send_ajax(){
			var j_data = {
				"cilent_info" : {
					"x" : 0,
					"y" : 0,
					"width" : 0,
					"height" : 0
				},
				"cilent_data" : {
					"inserted" : [],
					"changed" : [],
					"deleted" : []
				}
			};
			j_data.cilent_info.x = Area_x;
			j_data.cilent_info.y = Area_y;
			j_data.cilent_info.width = cilentWidth;
			j_data.cilent_info.height = cilentHeight;
			for(var i in Big_Boss.inserted){
				j_data.cilent_data.inserted.push(Big_Boss.inserted.shift());
			}
			for(var i in Big_Boss.changed){
				j_data.cilent_data.changed.push(Big_Boss.changed.shift());
			}
			for(var i in Big_Boss.deleted){
				j_data.cilent_data.deleted.push(Big_Boss.deleted.shift());
			}
			$.ajax({url: "process.php",
					data: { data: JSON.stringify(j_data)},
					type: "POST",
					async: true,
					success: function(data){
						var get_data;
						try{
							get_data = JSON.parse(data);
						}catch(e){

						}
						if(get_data != undefined){
							var change = new Array();
							var insert = new Array();
							var deleted = new Array();
							var result = new Array();
							var string = $.extend({}, j_data.cilent_data.changed, Big_Boss.changed);
							for(var i in string){
								change[string[i].id] = true;
							}
							var stringx =  $.extend({}, j_data.cilent_data.inserted, Big_Boss.inserted);
							for(var i in stringx){
								insert[stringx[i].id] = true;
							}
							var stringy =  $.extend({}, j_data.cilent_data.deleted, Big_Boss.deleted);
							for(var i in stringy){
								deleted[stringy[i]] = true;
							}
							for(var e in get_data){
								var id = get_data[e].id;
								result[id] = true;
								if(deleted[id] != undefined){
									continue;
								}
								if(MemoArray[id] == undefined){
									MemoArray[id] = new MEMO();
									MemoArray[id].id = id;
								}
								if(change[id] == undefined){
									MemoArray[id].setMEMO(parseInt(get_data[e].x), parseInt(get_data[e].y), get_data[e].title, get_data[e].content);
								}
							}
							for(var i in MemoArray){
								var id = MemoArray[i].id;
								if(result[id] == undefined && insert[id] == undefined){
									console.log("remove");
									$("#"+id).remove();
									delete MemoArray[id];
								}
							}
							delete j_data.cilent_data;
						}
						setTimeout("send_ajax()", 300);
					}
			});
		}
	</script>
	<script type="text/javascript" src="js/MEMO.js"></script>
	<script type="text/javascript" src="js/update.js"></script>
	<script type="text/javascript" src="js/RECT.js"></script>
	<!--
		<link rel="stylesheet" href="css/jquery-ui.css" />
		<script type="text/javascript" src="js/jquery-ui.js"></script>
	-->
</head>
<body>
	<div class="page">
		<div class="top">Hello <?php echo $user;?>, you are at the public room --- go to private_room</div>
		<div id="content_box" class="content">
			<div id="delete_btn" class="delete_btn"></div>
		</div>
		<div id="insert_btn" class="insert_btn"></div>
		
	</div>
	<script>
		
		var Recycle = new RECT();
		$(function(){
			//init();
			cilentWidth = $("#content_box").width();
			cilentHeight = $("#content_box").height();
			console.log($("#content_box"));
			$("#insert_btn").click(function(){
				MemoBuffer.push(insert_DATA(cilentWidth, cilentHeight));
			});
			$(document).mousemove(function(e){
				$(".moving").each(function(index){
					var ix=e.pageX+distance_x;//parseInt($(this).css("left"));
					var iy=e.pageY+distance_y;//parseInt($(this).css("top"));
					$(this).css("left", (e.pageX+distance_x));
					$(this).css("top", (e.pageY+distance_y));
					var id = $(this).attr("id");
					if(ChangeBuffer[id] == undefined){
						ChangeBuffer[id] = new MEMO();
						ChangeBuffer[id].id = id;
					}

					var content = $(this).children(".text").val();
					var title = $(this).children(".title").val();
					ChangeBuffer[id].setMEMO(ix, iy, title, content);
				});
			});
			$(window).resize(function(){
				cilentWidth = $("#content_box").width();
				cilentHeight = $("#content_box").height();
			});
			/*for(var i=0;i<10;i++){
				MemoBuffer.push(insert_DATA(cilentWidth, cilentHeight));
			}*/
			update();
			send_ajax();
		});
	</script>
</body>
</html>