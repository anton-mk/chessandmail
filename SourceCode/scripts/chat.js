var chat_count_messages =170;
var chat_messages_on_page =5;
var chat_count_visible_num_pages =5;
var chat_visible_page =1;
var chat_oXMLHTTP; //объект для отправки запросов
var chat_messages; //список сообщений
var chat_o_hints_; //объект выводящий подсказки, при наборе символов в поле Кому

function chat_create_elements(){
	chat_oXMLHTTP =createXMLHttp();
	chat_messages =[];

	if (chat_oXMLHTTP != null){
        var s ='font-family: Liberation Serif, Times, sans-serif; ' +
                    'font-size: 12pt; font-style: normal; font-weight: normal; color : black';
        document.getElementById("chat").innerHTML =
            '<span style="'+s+'">'+
            '   <div id="chat_labels" style="text-align:right"></div>' +
            '   <div id="chat_list_messages" style="text-align:left"></div>' +
            '   <br/>' +
            '   <table style="width:100%">' +
            '       <col span="2">' +
            '       <tbody>' +
            '           <tr>' +
            '               <td style="text-align:left">Сообщение</td>' +
            '               <td style="width:100%"><input id="chat_message" style="width:95%" type="text" id="message"></td>' +
            '           </tr>' +
            '           <tr>' +
            '              <td style="text-align:left">Кому</td>' +
            '              <td style="width:100%"><input id="chat_login" style="width:95%" type="text" id="login_addr" autocomplete="off"></td>' +
            '            </tr>' +
            '            <tr>' +
            '               <td  colspan="2" style="width:100%; text-align:right">'+
            '                   <input type="button" value="отправить" onclick="chat_add_message()">' +
            '               </td>' +
            '            </tr>' +
            '       </tbody>' +
            '   </table>' +
            '</span>';

        chat_read_info();

        chat_o_hints_ =new cl_hints_(document.getElementById("chat_login"),5,"ajax_chat.php");
	}
} //chat_create_elements

function chat_count_pages(){
	var result_=0;
	result_ =Math.floor(chat_count_messages / chat_messages_on_page);
	if ((chat_count_messages % chat_messages_on_page) != 0) result_++;
	return result_;
} //chat_count_pages

function chat_getFirstVisibleNum(){
	var result_=0;
	result_ =Math.floor(chat_visible_page / chat_count_visible_num_pages);
	if ((chat_visible_page % chat_count_visible_num_pages) == 0) result_--;
	result_=result_ * chat_count_visible_num_pages +1;
	return result_;
} //chat_getFirstVisibleNum

function chat_getLastVisibleNum(){
	var result_;
	var a;
	a =chat_count_pages();
	result_ =chat_getFirstVisibleNum();
	for(var i=2; (i <=chat_count_visible_num_pages) && (result_ < a); i++) result_++;
	return result_;
} //getLastVisibleNum

function chat_get_num_first_record(){ //от ноля
	var result_;
	result_ =((chat_visible_page-1)* chat_messages_on_page);
	return result_;
} //chat_get_num_first_record

function chat_create_label_pages(){
	var c;
	var s ='';
	c =chat_count_pages();
	if (c > 1){
		a =chat_getFirstVisibleNum();
		b =chat_getLastVisibleNum();
		if (a > 1) s ='<span style="color:blue; cursor:pointer" onclick="chat_visible_page='+(a-1)+'; chat_click_label()">&lt;&lt;</span>';
		for (var i=a; i <=b; i++){
			if (s != '') s +='&nbsp';
			if (chat_visible_page != i)
					s +='<span style="color:blue; cursor:pointer" onclick="chat_visible_page='+i+'; chat_click_label()">'+i+'</span>';
				else
					s +='<span>'+i+'</span>';
		} //for i
		if (b < chat_count_pages()) s +='&nbsp<span style="color:blue; cursor:pointer" onclick="chat_visible_page='+(b+1)+'; chat_click_label()">&gt;&gt;</span>';
	}
	document.getElementById("chat_labels").innerHTML =s;
} //chat_create_label_pages

function chat_str_for_post(message_,login_){
	var object_post =new Object();
	object_post.rec_count =chat_messages_on_page;
	if (typeof(message_) != "undefined") {
		object_post.message_add =message_;
		object_post.login_to =login_;
	} else{
		object_post.rec_start =chat_get_num_first_record();
	}
	return JSON.stringify(object_post);
} //chat_str_for_post

function chat_out_messages(){
	var s='';
	for(var i=0; i < chat_messages.length; i++){
		if (s != '') s +='<br/><br/>';
		s +='<span>'+chat_messages[i].loginFrom_ + '&nbsp;-&nbsp;' + chat_messages[i].loginTo_ + '</span>&nbsp;' +
			'<span>('+chat_messages[i].timeMake_ + ')</span><br>' +
			'<span>' +chat_messages[i].message_ + '</span>';
	}
	document.getElementById("chat_list_messages").innerHTML =s;
} //chat_out_messages

function chat_is_new_messages(result_object){
	result =false;
	if (result_object.messages_.length != chat_messages.length) result =true;
		else{
			for(var i=0; i < result_object.messages_.length; i++){
				if (result_object.messages_[i].id_ !=chat_messages[i].id_){
					result = true;
				break;
				}
			} //for
		}
	return result;
} //chat_is_new_messages

function chat_result_to_chat_messages(result_){
	chat_messages =[];
	for (var i=0; i < result_.messages_.length; i++){
		chat_messages[i] =new Object();
		chat_messages[i].id_        =result_.messages_[i].id_;
		chat_messages[i].message_   =result_.messages_[i].message_;
		chat_messages[i].timeMake_  =result_.messages_[i].timeMake_;
		chat_messages[i].loginFrom_ =result_.messages_[i].loginFrom_;
		chat_messages[i].loginTo_   =result_.messages_[i].loginTo_;
	}
} //chat_result_to_chat_messages

function chat_onreadystatechange(){
	var result_object;
	if (chat_oXMLHTTP.readyState ==4){
		if (chat_oXMLHTTP.status ==200){
//			alert("text-"+chat_oXMLHTTP.responseText);
			result_object =JSON.parse(chat_oXMLHTTP.responseText);
			if (result_object.result_ ===''){
				if (chat_count_messages !=result_object.countMessage){
					chat_count_messages =result_object.countMessage;
					chat_create_label_pages();
				}
				if (chat_is_new_messages(result_object)){
					chat_result_to_chat_messages(result_object);
					chat_out_messages();
				}
			}
		}
		window.setTimeout("chat_read_info()",5000);
	}
} //chat_onreadystatechange

function chat_read_info(){
	if (chat_oXMLHTTP.readyState !=0)
		chat_oXMLHTTP.abort();
	chat_oXMLHTTP.open("post","ajax_chat.php",true);
	chat_oXMLHTTP.onreadystatechange=chat_onreadystatechange;
	chat_oXMLHTTP.send(chat_str_for_post());
} //chat_read_info

function chat_add_message(){
	var result_object;
	if (ltrim(document.getElementById("chat_message").value) ===''){
		window.alert("Сообщение не указано.");
		return;
	}else if (ltrim(document.getElementById("chat_login").value) ===''){
		window.alert("Логин назначения не указан.");
		return;
	}
	if (chat_oXMLHTTP.readyState !=0)
		chat_oXMLHTTP.abort();
	chat_oXMLHTTP.open("post","ajax_chat.php",false);
	chat_oXMLHTTP.send(chat_str_for_post(document.getElementById("chat_message").value,document.getElementById("chat_login").value));
	if (chat_oXMLHTTP.status ==200){
//		alert("text-"+chat_oXMLHTTP.responseText);
		result_object =JSON.parse(chat_oXMLHTTP.responseText);
		if (result_object.result_ =='OK'){
			chat_visible_page =1;
			chat_count_messages =result_object.countMessage;
			chat_create_label_pages();
			chat_result_to_chat_messages(result_object);
			chat_out_messages();
			document.getElementById("chat_message").value ='';
			document.getElementById("chat_login").value ='';
		} else window.alert(result_object.result_);
	}
	chat_read_info();
} //chat_add_message

function chat_click_label(){
	var result_object;
	if (chat_oXMLHTTP.readyState !=0)
		chat_oXMLHTTP.abort();
	chat_oXMLHTTP.open("post","ajax_chat.php",false);
	chat_oXMLHTTP.send(chat_str_for_post());
	if (chat_oXMLHTTP.status ==200){
//		alert("text-"+chat_oXMLHTTP.responseText);
		result_object =JSON.parse(chat_oXMLHTTP.responseText);
		if (result_object.result_ ===''){
			chat_count_messages =result_object.countMessage;
			chat_create_label_pages();
			chat_result_to_chat_messages(result_object);
			chat_out_messages();
		} else window.alert(result_object.result_);
	}
	chat_read_info();
} //chat_click_label
