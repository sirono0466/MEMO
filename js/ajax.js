function ajax_packet(){
	this.create_packet = function(ix, iy, i_w, i_h){
		this.j_data = {
			"cilent_info" : {
				"x" : ix,
				"y" : iy,
				"width" : i_w,
				"height" : i_h
			},
			"cilent_data" : {
				"inserted" : [],
				"changed" : [],
				"deleted" : []
			}
		};
	}
	this.clear_data = function(){
		delete this.j_data;
	}
	this.update_RECT = function(ix, iy, i_w, i_h){
		this.x = ix;
		this.y = iy;
		this.w = i_w;
		this.h = i_h;
	}
}
function send_ajax(packet){
	$.ajax({url: "process.php",
		data: { data : JSON.stringify(packet.j_data)},
		type: "POST",
		async: true,
		success: function(data){
			console.log(data);
			packet.clear_data();
			packet.create_packet();
			setTimeout("send_ajax(packet)", 300);
		}
	});
}