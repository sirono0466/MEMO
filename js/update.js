function update(){
	save_DATA();
	fill_content();
	setTimeout("update()", 300);
}