function pos(){
			this.x = 0;
			this.y = 0;
		}
function MEMO(){
	this.id=0;
	this.title="";
	this.content="";
	this.position = new pos();
	this.setPos = function(ix, iy){
		this.x = ix;
		this.y = iy;
	}
	this.setTitle = function(text){
		delete this.title;
		this.title = text;
	}
	this.setContent = function(text){
		delete this.content;
		this.content = text;
	}
	this.setMEMO = function(ix, iy, Title, Content){
		this.setPos(ix, iy);
		this.setTitle(Title);
		this.setContent(Content);
	}
}

function save_DATA(){
	//create json, ajax
	/*var j_data = {
		"cilent_info" : {
			"x" : Area_x,
			"y" : Area_y,
			"width" : $('#content_box').width(),
			"height" : $('#content_box').height()
		},
		"cilent_data" : {
			"inserted" : [],
			"changed" : [],
			"deleted" : []
		}
	};*/


	for(var i in DeleteBuffer){
		var id = DeleteBuffer[i];
		Big_Boss.deleted.push(DeleteBuffer[i].slice());
		delete MemoArray[id];
		delete MemoBuffer[id];
		delete ChangeBuffer[id];
		delete DeleteBuffer[i];
	}DeleteBuffer = [];
	for(var i in MemoBuffer){
		var id = MemoBuffer[i].id;
		//var id = MemoBuffer[i].id;
		if(MemoArray[id] == undefined){
			MemoArray[id] = new MEMO();
		}
		MemoArray[id].id = id;
		MemoArray[id].setMEMO(MemoBuffer[i].x, MemoBuffer[i].y, MemoBuffer[i].title, MemoBuffer[i].content);
		Big_Boss.inserted.push({
			"x" : Area_x + MemoBuffer[i].x,
			"y" : Area_y + MemoBuffer[i].y,
			"title" : MemoBuffer[i].title.slice(),
			"content" : MemoBuffer[i].content.slice()
		});
		delete MemoBuffer[i];
	}MemoBuffer = [];
	for(var i in ChangeBuffer){
		if(MemoArray[i] == undefined){
			continue;
		}console.log(ChangeBuffer[i].id);
		MemoArray[i].setMEMO(ChangeBuffer[i].x, ChangeBuffer[i].y, ChangeBuffer[i].title, ChangeBuffer[i].content);
		Big_Boss.changed.push({
			"id" : ChangeBuffer[i].id,
			"x" : Area_x + ChangeBuffer[i].x,
			"y" : Area_y + ChangeBuffer[i].y,
			"title" : ChangeBuffer[i].title.slice(),
			"content" : ChangeBuffer[i].content.slice()
		});
		delete ChangeBuffer[i];
	}ChangeBuffer = [];
	/*
	*/
}
function insert_DATA(cilentWidth, cilentHeight){
	var memo_data = new MEMO();
	memo_data.id = -StackSort.length;
	memo_data.setMEMO(cilentWidth/2, cilentHeight/2, "", "");
	StackSort.push(memo_data.id);
	return memo_data;
}
function create_divBox(e){
	var content = "<div id='" + e.id + "' class='memo unselectable'>" +
					"<textarea class='title none_texting' placeholder='Title Here...'>" + e.title + "</textarea>"+
					"<textarea class='text none_texting' placeholder='Content Here...'>" + e.content + "</textarea>"+
					//"<div class='text'>" + e.content + "</div>"+
				"</div>";
	return content;
}
function fill_content(){
	for(var i in MemoArray){
		if(!$("#"+MemoArray[i].id).length){
				console.log("id: "+ MemoArray[i].id +", x: "+MemoArray[i].x+", y: "+MemoArray[i].y+", Title: \""+MemoArray[i].title+"\", Content: \""+MemoArray[i].content+"\"");
				$("#content_box").append(create_divBox(MemoArray[i]));
				//$("#"+MemoArray[i].id).draggable();
				var id = MemoArray[i].id;
				$("#"+id).mousedown(function(e){
					$(this).addClass("moving");
					distance_x = parseInt($(this).css("left")) - e.pageX;
					distance_y = parseInt($(this).css("top")) - e.pageY;
					$(this).parent("#content_box").append($(this));
					//event.stopPropagation();
				});
				$("#"+id).mouseup(function(e){
					$(this).removeClass("moving");
					var x = e.pageX;
					var y = e.pageY;
					Recycle.countSize($(".delete_btn"));
					if(Recycle.isInclude(x,y)){
						var id = $(this).attr("id");
						DeleteBuffer.push(id);
						$(this).remove();
					}
				});
				$("#"+id).children().focus(function(){
					$(this).removeClass("none_texting");
					console.log("focus");
				});
				$("#"+id).children().blur(function(){
					$parent = $(this).parent(".memo");
					var ix=parseInt($parent.css("left"));
					var iy=parseInt($parent.css("top"));
					var id = $parent.attr("id");
					if(ChangeBuffer[id] == undefined){
						ChangeBuffer[id] = new MEMO();
						ChangeBuffer[id].id = id;
					}

					var content = $parent.children(".text").val();
					var title = $parent.children(".title").val();
					ChangeBuffer[id].setMEMO(ix, iy, title, content);
					$(this).addClass("none_texting");
					console.log("blur");
				});
		}
		var id = MemoArray[i].id;
		var Element_id = "#"+MemoArray[i].id;
		$(Element_id).css({top: MemoArray[i].y, left: MemoArray[i].x});
		$(Element_id).children(".title.none_texting").val(MemoArray[i].title);
		$(Element_id).children(".text.none_texting").val(MemoArray[i].content);
	}
}
