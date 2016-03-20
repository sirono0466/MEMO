function RECT(ix, iy, iw, ih){
	this.x = ix;
	this.y = iy;
	this.width = iw;
	this.height = ih;
	this.countSize = function(e){
		var offset = e.offset();
		this.x = parseInt(offset.left);
		this.y = parseInt(offset.top);
		this.width = parseInt(e.css("width"));
		this.height = parseInt(e.css("height"));
	}
	this.isInclude = function(ix, iy){
		var x = this.x;
		var y = this.y;
		var rx = x + this.width;
		var by = y + this.height;
		if(ix>=x && ix<=rx && iy>=y && iy<=by){
			return true;
		}
		return false;
	}
}