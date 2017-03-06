var action_open = 0;

function show_favorite() {
	if(action_open == 0) {
		action_open = 1;
		
		var unique_id = document.getElementById("get_unique_id").value;
		
		var x = document.createElement('div');
		x.innerHTML = '<br /><div style="border: 1px solid #6A259C; width: 85%;"><form action="http://www.mvland.com/watch/mv=unique_id" method="post" id="message_form_fly"><table border="0" width="100%"><tr><td><strong>確定存此MV到我的最愛?</strong></td></tr><tr><td height="5"></td></tr><tr><td><input type="submit" value="送出" style="width: 100px; height: 30px;" /><input type="button" value="取消" onclick="hide_favorite()" style="width: 100px; height: 30px;" /><td></tr></table><input type="hidden" name="add_favorites" /></form></div>';
		document.getElementById('insert_fav_field').appendChild(x);
			
		var correct_id = document.getElementById('message_form_fly');
		correct_id.action = 'http://www.mvland.com/watch/mv='+unique_id;
		
		var save_favorite = document.getElementById('save_favorite');
		var favorite_link = document.getElementById('favorite_link');
		save_favorite.removeChild(favorite_link);
		
		var a = document.createElement('span');
		a.setAttribute('id', 'span_id');
		a.innerHTML = '存到最愛';
		document.getElementById('save_favorite').appendChild(a);
	} else {
		alert("請先送出或取消開啟中的視窗再執行此動作");
	}
}

function hide_favorite() {
	var node = document.getElementById('insert_fav_field');
	node.removeChild(node.childNodes[0]);
	
	var save_favorite = document.getElementById('save_favorite');
	var span_id = document.getElementById('span_id');
	save_favorite.removeChild(span_id);	
	
	var r = document.createElement('span');
	r.setAttribute('id', 'favorite_link');
	r.innerHTML = '<a href="Javascript: show_favorite();">存到最愛</a>';
	document.getElementById('save_favorite').appendChild(r);
	
	action_open = 0;
}	

function show_comment() {
	if(action_open == 0) {
		action_open = 1;
		
		var unique_id = document.getElementById("get_unique_id").value;
	
		var x = document.createElement('div');
		x.innerHTML = '<br /><div style="border: 1px solid #6A259C; width: 85%;"><strong>發表意見:</strong><form action="http://www.mvland.com/watch/mv=unique_id" method="post" id="message_form_fly" onsubmit="return validate(this);"><table border="0" width="100%"><tr><td><textarea name="message" id="message_field" style="width: 100%; height: 90px; border: 1px solid #999999;" /></textarea></td></tr><tr><td height="5"></td></tr><tr><td><input type="submit" value="送出" style="width: 100px; height: 30px;" /><input type="button" value="取消" onclick="hide_comment()" style="width: 100px; height: 30px;" /></td></tr></table><input type="hidden" name="add_comments" /></form></div>';
		document.getElementById('insert_msg_field').appendChild(x);
			
		var correct_id = document.getElementById('message_form_fly');
		correct_id.action = 'http://www.mvland.com/watch/mv='+unique_id;
		
		var insert_comment = document.getElementById('insert_comment');
		var comment_link = document.getElementById('comment_link');
		insert_comment.removeChild(comment_link);
	
		var a = document.createElement('span');
		a.setAttribute('id', 'span_id');
		a.innerHTML = '發表意見';
		document.getElementById('insert_comment').appendChild(a);
	} else {
		alert("請先送出或取消開啟中的視窗再執行此動作");
	}
}
	
function hide_comment() {
	var node = document.getElementById('insert_msg_field');
	node.removeChild(node.childNodes[0]);
	
	var insert_comment = document.getElementById('insert_comment');
	var span_id = document.getElementById('span_id');
	insert_comment.removeChild(span_id);	
	
	var r = document.createElement('span');
	r.setAttribute('id', 'comment_link');
	r.innerHTML = '<a href="Javascript: show_comment();">發表意見</a>';
	document.getElementById('insert_comment').appendChild(r);
	
	action_open = 0;	
}	

function show_flag() {
	if(action_open == 0) {
		action_open = 1;

		var unique_id = document.getElementById("get_unique_id").value;
	
		var x = document.createElement('div');
		x.innerHTML = '<br /><div style="border: 1px solid #6A259C; width: 85%;"><form action="http://www.mvland.com/watch/mv=unique_id" method="post" id="message_form_fly"><strong>請選擇舉報的原因:</strong><table border="0" width="100%"><tr><select name="flag_option" style="width: 150px;"><option value="1">MV與標題不符</option><option value="2">歌詞與標題不符</option><option value="3">MV已不存在</option></select></td></tr><tr><td><div style="padding-top: 10px;"></div></td></tr><td><input type="submit" value="送出" style="width: 100px; height: 30px;" /><input type="button" value="取消" onclick="hide_flag()" style="width: 100px; height: 30px;" /><td></tr></table><input type="hidden" name="report_flag" /></form></div>';
		document.getElementById('insert_flg_field').appendChild(x);
			
		var correct_id = document.getElementById('message_form_fly');
		correct_id.action = 'http://www.mvland.com/watch/mv='+unique_id;
		
		var report_flag = document.getElementById('report_flag');
		var flag_link = document.getElementById('flag_link');
		report_flag.removeChild(flag_link);
		
		var a = document.createElement('span');
		a.setAttribute('id', 'span_id');
		a.innerHTML = '舉報問題';
		document.getElementById('report_flag').appendChild(a);
	} else {
		alert("請先送出或取消開啟中的視窗再執行此動作");
	}		
}

function hide_flag() {
	var node = document.getElementById('insert_flg_field');
	node.removeChild(node.childNodes[0]);
	
	var report_flag = document.getElementById('report_flag');
	var span_id = document.getElementById('span_id');
	report_flag.removeChild(span_id);	
	
	var r = document.createElement('span');
	r.setAttribute('id', 'flag_link');
	r.innerHTML = '<a href="Javascript: show_flag();">舉報問題</a>';
	document.getElementById('report_flag').appendChild(r);

	action_open = 0;	
}	

function share_mv() {
	var unique_id = document.getElementById("get_unique_id").value;
	window.open('http://www.mvland.com/share_mv?url=' + unique_id,'Share','scrollbars=yes,menubar=no,height=480,width=350,resizable=yes,toolbar=no,location=no,status=no');
}