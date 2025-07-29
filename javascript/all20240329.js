function IsNumeric(input){
    var RE = /^-{0,1}\d*\.{0,1}\d+$/;
    return (RE.test(input));
}

function ReNumeric(input){
	var str=''; for (i = 0; i < input.length; i++){  var a = input.charAt(i);if(IsNumeric(a)==true){ str += a; }else{ continue; } } return str;
}

  		function ConfirmDel(){
			if 	 (confirm ("Do you want to delete data?")==true){
				return true;
			}
				return false;
		}


function msgAlert(obj,msg,op){
	switch( parseInt(op,10) ){
		case 1 : // Alert Focus return false
			window.alert(msg); obj.focus(); return false;
		break;
		case 2 : // Confirm Message Someting
			return confirm(msg);
		break;		
		default : // Alert Message Warning
			if(obj.value==''){  window.alert(msg); obj.focus(); return false; }else{ return true; }		
		break;
	}
}

function pageWidth(){
return window.innerWidth != null ? window.innerWidth : document.documentElement && document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body != null ? document.body.clientWidth : null;
}

function pageHeight(){
return window.innerHeight != null? window.innerHeight : document.documentElement && document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body != null? document.body.clientHeight : null;
}

function topPosition(){
return typeof window.pageYOffset != 'undefined' ? window.pageYOffset : document.documentElement && document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop ? document.body.scrollTop : 0;
}

function leftPosition(){
return typeof window.pageXOffset != 'undefined' ? window.pageXOffset : document.documentElement && document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft ? document.body.scrollLeft : 0;
}

function setHideScroll(hide){
	if(hide == "block"){   document.body.scroll = "no"; document.body.style.overflow = "hidden";
	}else{  	document.body.scroll = "yes"; document.body.style.overflow = "";   }
}
/*--------------------------------------------------------------------------------*/
	function openWins(URL, N, w, h, status, scrollbars, menubar, toolbar, resizable){ 
		var x = (screen.availWidth-w)/2; 
		var y = (screen.availHeight-h)/2;
		var strOpton = ',toolbar='+arguments[7]+',location=0,directories=0,status='+arguments[4]+',resizable='+arguments[8]+',menubar='+arguments[6]+',scrollbars='+arguments[5]+',copyhistory=no';
		switch(navigator.appName){
			case "Netscape" : winProp = "width="+w+",height="+h+",screenX="+x+",screenY="+y+strOpton+""; break;
			case "Opera" : winProp =	"width="+w+",height="+h+",left="+x+",top="+(y/2)+strOpton+""; break;			
			default : winProp =	"width="+w+",height="+h+",left="+x+",top="+y+strOpton+"";break;  /*IE and  other */
		}		
		Win = window.open(URL, N, winProp);
		if(parseInt(navigator.appVersion,10) >= 4) { Win.window.focus(); }
	}
/*--------------------------------------------------------------------------------*/
	function writeCookie(name, value, hours){
	  var expire = "";
	  if(hours != null){
		expire = new Date((new Date()).getTime() + hours * 3600000);
		expire = "; expires=" + expire.toGMTString();
	  } document.cookie = name + "=" + escape(value) + expire;
	}
/*--------------------------------------------------------------------------------*/
	function readCookie(name){
	  var cookieValue = "";
	  var search = name + "=";
	  if(document.cookie.length > 0){ 
		offset = document.cookie.indexOf(search);
		if (offset != -1){ 
		  offset += search.length;
		  end = document.cookie.indexOf(";", offset);
		  if (end == -1) end = document.cookie.length;
		  cookieValue = unescape(document.cookie.substring(offset, end))
		}
	  }  return cookieValue;
	}
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/	
	function genString(xlength,op){
		var keylist="abcdefghijklmnopqrstuvwxyz123456789";
		this.str = "";
		for (i=0;i<xlength;i++){
			this.str += keylist.charAt(Math.floor(Math.random()*keylist.length));
		} return this.str;
	}

	// Replace ข้อความใน string ตามต้องการ เช่น replaceStr(strContent,'\n',':');
	function replaceStr(strString,strFind,strReplace) {
		while (strString.indexOf(strFind)>-1) {
			intPosition= strString.indexOf(strFind);
			strString = '' + (strString.substring(0, intPosition) + strReplace +
			strString.substring((intPosition + strFind.length), strString.length));
		}
		return strString;
	}	
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function characters(str,op){
		if (str.length>1){return false;}
			var str0 = '_';
			var str1 = '0123456789';	
			var str2 = '๐๑๒๓๔๕๖๗๘๙';	
			var str3 = 'abcdefghijklmnopqrstuvwxyz';		
			var str4 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';	
			var str5 = 'กขฃคฅฆงจฉชซฌญฎฏฐฑฒณดตถทธนบปผฝพฟภมยรลวศษสหฬอฮ';
			var str6 = '   ั   ิ   ี   ึ   ื  ุ   ู  ็  ่  ้   ๊  ๋  ์ เ แ โ ใ ไ ะ า ำ ฯ ๆ ฤ ฦ';
			var str7 = '/-.()';
			var str8 = '@.-_';
			var str9 = '-';
			var str10 = '()';		
			var str11 = ' ';	
			var str12 = '.,';		
			var str13 = '¬!"£$%^&*()`{}[]:@~;#<>?,.-=_+|';	
			switch( parseInt(op,10) ){
				case 1 : string = ''+str1; break; // 0-9 ตัวเลขอารบิก
				case 2 : string = ''+str0+str1+str3; break; // username
				case 3 : string = ''+str1+str4; break;// toUpperCase A-Z , 0-9
			}
		if (string.indexOf(str)!=-1){return true;}
		return false;
	}
	
	function checkInput(str,op){
		var i,str_true='';
		for (i = 0; i < str.length; i++){   
			var cstr = str.charAt(i);
			if(characters(cstr,op)==true){ str_true += cstr; }else{ continue; }
		} return str_true;
	}
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function trim_Empty(str){
		for(i=0; i<str.length; i++){
			 str = str.replace(String.fromCharCode(160),"");
			 str = str.replace(/^\s*|\s*$/g, "");
		 }
		return str.replace(/^\s*|\s*$/g, "");
	}	
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function submitForm(objF,f_target,f_action){  
		objF.setAttribute('target',f_target);
		objF.setAttribute('action',f_action);
		objF.submit(); 	
	} 
	function FormDisableObj(objF,TF){
		for (var i=0;i<objF.elements.length;i++){ objF.elements[i].disabled = TF; }
	}	
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function $(){
		var elements = new Array();
		for (var i = 0; i < arguments.length; i++) {
			var element = arguments[i];
			if (typeof element == 'string')
				element = document.getElementById(element);
			if (arguments.length == 1)
				return element;
				elements.push(element);
		}
		return elements;
	}	
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function ext_checkFiles(strs,op){
		var str = new String(strs);
		str = str.toLowerCase();
		var imgReg = '';
		switch(parseInt(op,10)){
			case 1 :  imgReg = /^.+\.(jpg|jpeg|gif|png)$/i;  break; 
			case 2 :  imgReg = /^.+\.(flv|swf)$/i;  break; 		
			case 3 :  imgReg = /^.+\.(rar|zip)$/i;  break; 
			case 4 :  imgReg = /^.+\.(rar|zip|pdf)$/i;  break; 		
			
			default : break;
		}		
		if (str.search(imgReg) != -1){  return true; }else{ return false; }
	}

	function cInputTypefile(obj,op,span_id,str_alert){
		var re = ext_checkFiles(obj.value,op); // jpg|jpeg|gif|png
		if(re==false){
			window.alert(""+str_alert+"");
			$(''+span_id+'').innerHTML = "<input name=\""+obj.id+"\" type=\"file\" onpaste=\"return false;\" onKeyPress=\"return false;\" onKeUp=\"return false;\" id=\""+obj.id+"\" onChange=\"cInputTypefile(this,"+op+",'"+span_id+"','"+str_alert+"');\" >"; 
		}
	}
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function disableObjectSRs(result,objArray){
		if(result==true){
			for (var i=0;i<objArray.length;i++){ $(''+objArray[i]+'').disabled = true; }
		}else{
			for (var i=0;i<objArray.length;i++){ $(''+objArray[i]+'').disabled = false; }
		}
	}
	function disableGroup(id,arrA,arrB){ // if = none
			if($(''+id+'').disabled==true){
				disableObjectSRs(false,arrA);
				disableObjectSRs(true,arrB);
			}else{
				disableObjectSRs(true,arrA);
				disableObjectSRs(false,arrB);
			}	 
	} 
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function displayObjectSRs(result,objArray){
		if(result=='none'){
			for (var i=0;i<objArray.length;i++){ $(''+objArray[parseInt(i,10)]+'').style.display = 'block'; }
		}else{
			for (var i=0;i<objArray.length;i++){ $(''+objArray[parseInt(i,10)]+'').style.display = 'none'; }
		}
	}
	function displayGroup(id,arrA,arrB){ // if = none
			if($(''+id+'').style.display=='none'){
				displayObjectSRs('block',arrA);
				displayObjectSRs('none',arrB);				
			}else{
				displayObjectSRs('none',arrA);
				displayObjectSRs('block',arrB);	
			}	 
	} 
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function valueObjectSRs(objArray){
		for (var i=0;i<objArray.length;i++){ $(''+objArray[parseInt(i,10)]+'').value = ''; }
	}
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function setSelectList(formName, selectListName, inValue){
		var e = self;
		if (inValue != null){
			for (var i = (e.document[formName][selectListName].length - 1); i >= 0 ; i--){
				if(inValue.toString() == (e.document[formName][selectListName].options[i].value.toString())){
					e.document[formName][selectListName].options[i].selected = true;
				}
			}
		}
	}
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/			
  	function checkDateDiff(date1,date2){
		var obj1 = $(''+date1+'');
		var str1 = Array(3); var d1=0,m1=0,y1=0; str1 = obj1.value.split('-');
		d1 = parseInt(str1[0],10); m1 = parseInt(str1[1],10);	 y1 = parseInt(str1[2],10);		

		var obj2 = $(''+date2+'');		
		var str2 = Array(3); var d2=0,m2=0,y2=0; str2 = obj2.value.split('-');
		d2 = parseInt(str2[0],10); m2 = parseInt(str2[1],10);	 y2 = parseInt(str2[2],10);		

		var dateS = US30diff(y2, m2, d2, y1, m1, d1);
		if(parseInt(dateS,10)<0){
			alert('! เลือกวันที่ไม่ถูกต้อง ตรวจสอบ วันที่ก่อน-หลังให้ถูกต้อง ค่ะ');
			obj2.value = obj1.value;
			return false;
		}
	}
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function EU30diff(Y2, M2, D2, Y1, M1, D1) { var X; // should do
	  X = ((Y2-Y1)*12 + (M2-M1))*30 + (D2-(D2>30))-(D1-(D1>30));
	  return X; 
	 }
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function US30diff(Y2, M2, D2, Y1, M1, D1){ var X; // might do
	  X = ((Y2-Y1)*12 + (M2-M1))*30 + (D2-(D2>30))-(D1-(D1>30&&D2>29));
	  return X ;
	 }
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	 function ScrollTo_xy(n){
		window.scrollTo(screen.width,parseInt(n,10));	 
	}
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function DisableAllSelect(flag){
		var ddl = document.getElementsByTagName('select');
		for (i=0;i<ddl.length;i++){
		 ddl[i].style.display = ''+flag+'';
		}    
	}
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function DisableOnceDdl(flag,id){
	  document.getElementById(''+id+'').style.display = ''+flag+'';
	}
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function getObjInnerText (obj) { 
		return (obj.innerText) ? obj.innerText : (obj.textContent) ? obj.textContent : ''; 
	} 
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function getDataFromUrl(){
	  var url = new String( window.location );
	  var len_url = url.length;
	  start_data = url.indexOf( "?" );
	  if ( start_data < 0 ) return; var alldata = url.substring( start_data+1, len_url );
		  return alldata;
	}
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
	function defaultImg(obj){
		obj.src = "../img/default.gif";	
	}
	function bgImage(obj,url){
		obj.style.backgroundImage="url('"+url+"')";
	}	
	function ClassCSS_id(id_,css){
		$(''+id_+'').className =""+css+"";
	}		

/*5555555555555555555555555555555555555555555555555555555555555555555555555*/		
//	addEvent(window, 'load', function1 );
//	addEvent(window, 'load', function2 );
var addEvent = function( obj, type, fn ) {
	if (obj.addEventListener)
		obj.addEventListener(type, fn, false);
	else if (obj.attachEvent) 
		obj.attachEvent('on' + type, function() { return fn.apply(obj, new Array(window.event));} );
}	
/*5555555555555555555555555555555555555555555555555555555555555555555555555*/	

// SELECT PCODE
function selectPcode(pcode)
{
	//alert("");
if (pcode=="")
	  {
	 //document.getElementById("txtHint").innerHTML="";
	  	document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
	  return;
	  }
	  
if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
xmlhttp.onreadystatechange=function()
  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
		//document.getElementById("part").select();
		}
  }

xmlhttp.open("GET","spcode.php?code="+pcode,true);
xmlhttp.send();

}

function CheckCSV() {
type_file = document.getElementById('fileCSV').value;
length_file = document.getElementById('fileCSV').value.length;
file_name = type_file;

 if (type_file.substring(type_file.lastIndexOf('.') + 1,length_file) !="csv" )
{
alert( 'For .CSV file Only' ) ;
document.getElementById('fileCSV').innerHTML ="";
return false ;
}else{

document.getElementById('fileCSV').innerHTML ="<img src='"+file_name+"'><br>";
}


}



/*-------------------------------------------------------------------------------------------------------------*/
function chkLSn(model)
{
var modeln = document.getElementById("model").value;
if(modeln != "")
{
var url = 'chk_sn.php?model='+modeln;

	xmlhttp=uzXmlHttp();
	xmlhttp.onreadystatechange = function () { 
	//document.getElementById("loading").innerHTML="<img src='images/general/loading.gif'>";//Show image ajax
	if (xmlhttp.readyState==4){
		if (xmlhttp.status==200) {
		//document.getElementById("loading").innerHTML="";
            var ret=xmlhttp.responseText;			
		//document.getElementById("show").innerHTML=ret;
		
		//alert (ret.length);
		
		if(ret.length == 2){
			alert("Error, please contact administrator.");
			/*document.getElementById("partnum").value = "";
			document.getElementById("partnum").focus();*/
			} else {
			document.getElementById("newsn").value = ret;
		 }

        }
	}
	}
	xmlhttp.open("GET", url);
	xmlhttp.setRequestHeader("If-Modified-Since", ""+ new Date().getTime()+"");
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=windows-874');
	xmlhttp.send(null);
	}//if(partnum != "")
  }


/********************************-*/
function ckKeyPresse(e) 
{
    // look for window.event in case event isn't passed in
    e = e || window.event;
    if (e.keyCode == 13)
    {
        document.getElementById('btnscan').click();
	//	alert( document.getElementById('btnscan').value)
		 return false;
   
    }    return true;

    
}//  if (e.keyCode == 13)

/*-------------Start Real time printing - print.php---------------------------*/
function validate(scan) {
	var sModel=document.getElementById("model").value;
	//alert(sModel);
	if (sModel == ""){
		//alert ("Please scan model no.");
		document.getElementById("model").focus();
		document.getElementById("txtStatus").innerHTML  ='<span class="txt-red-b-s" >กรุณาแสกนโมเดล</span>';//Please scan model no.
		return false;
		}else{
			
			if(sModel.length >="15"){
			
			//S-------------------------------------------------------------------------------------------	
				if (window.XMLHttpRequest) {
					// code for IE7+, Firefox, Chrome, Opera, Safari
					
					xmlhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.open("GET","chkmodel.php?qcmodel="+sModel,true);
				xmlhttp.onreadystatechange = function() {
					
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					  // document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
						var qrTxt= xmlhttp.responseText;
						var rqrTxt= qrTxt.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '')
						//alert(rqrTxt);
						if(rqrTxt !="No"){
					//	alert(sModel.length);
						document.getElementById("txtStatus").innerHTML  ='<span class="txt-green-b-s" ><img src="../../images/yes.gif"  /><br/>ระบบกำลังบันทึกข้อมูล...</span>';//Please scan model no.
						//alert(document.getElementById("hslid").value);
						document.getElementById("scan").submit();
						
						}else{
							//alert("Please input model No.(15 Digit)")
							document.getElementById("model").select();
							document.getElementById("txtStatus").innerHTML  ='<span class="txt-red-b-s" ><img src="../../images/no.gif"  /><br/>ไม่พบข้อมูล Ticket No. กรุณาแสกนข้อมูลที่ถูกต้อง</span>';
							//ไม่พบข้อมูลโมเดลในระบบ กรุณาแสกนโมเดลที่ถูกต้อง
						}//if(rqrTxt !="No"){
		
					}
					
				};
			// xmlhttp.send();
			 xmlhttp.send(null)
			
			
			//E-------------------------------------------------------------------------------------------		
			} else if (sModel.length =="9"){
				//alert(sModel.length);
				//S-------------------------------------------------------------------------------------------	
				if (window.XMLHttpRequest) {
					// code for IE7+, Firefox, Chrome, Opera, Safari
					
					xmlhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.open("GET","chkticket.php?qcmodel="+sModel,true);
				xmlhttp.onreadystatechange = function() {
					
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					  // document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
						var qrTxt= xmlhttp.responseText;
						var rqrTxt= qrTxt.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '')
						//alert(rqrTxt);
						if(rqrTxt !="No"){
					//	alert(sModel.length);
						document.getElementById("txtStatus").innerHTML  ='<span class="txt-green-b-s" ><img src="../../images/yes.gif"  /><br/>ระบบกำลังบันทึกข้อมูล...</span>';//Please scan model no.
						//alert(document.getElementById("hslid").value);
						document.getElementById("scan").submit();
						
						}else{
							//alert("Please input model No.(15 Digit)")
							document.getElementById("model").select();
							document.getElementById("txtStatus").innerHTML  ='<span class="txt-red-b-s" ><img src="../../images/no.gif"  /><br/>ไม่พบข้อมูล Ticket No. งานเศษในระบบ กรุณาแสกนข้อมูลที่ถูกต้อง</span>';
						}//if(rqrTxt !="No"){
		
					}
					
				};
			// xmlhttp.send();
			 xmlhttp.send(null)
			
			
			//E-------------------------------------------------------------------------------------------		
				
			}else{
					//alert("Please input model No.(15 Digit)")
					document.getElementById("model").select();
					document.getElementById("txtStatus").innerHTML  ='<span class="txt-red-b-s" ><img src="../../images/no.gif"  /><br/>ไม่พบข้อมูลโมเดลในระบบ กรุณาแสกนโมเดลที่ถูกต้อง<br/> หรือติดต่อ ASP Administrator เพื่อเพิ่มข้อมูล</span>';
					return false ;
					
					}//if(str.length=15){
				
		}
		
}// page print
/*------------EndReal time printing - print.php---------------------------------*/



/*Start Real time printing - printtag.php*/
function validateTag(my_request) {
	var sModel=document.getElementById("model").value;
	var subModel = sModel.substring(0, 15);
	var sTag= document.getElementById("txtTag").innerHTML;
	var tmodel= document.getElementById("txtModel").innerHTML;
					//alert(tmodel); 
	if (sModel == ""){
	
		document.getElementById("model").focus();
		document.getElementById("txtStatus").innerHTML  ='<span class="txt-red-b-s" ><img src="../../images/no.gif"  /> <br/>กรุณาแสกนซีเรียลลาเบล</span>';//Please scan model no.
		return false;
		}else{
			
			
			var fsting = sModel.substring(0, 2);
			if(fsting == "EW" || fsting == "GB" || fsting == "GC"  || fsting == "GD" ){ //GB GC GD 
				//At here, It's can check per model if they need 
				//document.getElementById("scan").submit();
					//S-------------------------------------------------------------------------------------------	
					if (window.XMLHttpRequest) {
						// code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} else {
						// code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.open("GET","chklabel2.php?tgno="+sTag+"&modelsr="+sModel+"&trmodel="+tmodel,true); 
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						  // document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
							var qrTxt= xmlhttp.responseText;
							var rqrTxt= qrTxt.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '')
							//alert(rqrTxt);
							if(rqrTxt =="1"){
							document.getElementById("txtStatus").innerHTML  = '<span class="txt-green-b-s" ><img src="../../images/yes.gif" /><br/>ข่้อมูลถูกต้อง ระบบกำลังบันทึกข้อมูล...</span>';//Please scan model no.
							document.getElementById("scan").submit();
							}else if(rqrTxt =="No"){
								document.getElementById("model").select();
								document.getElementById("txtStatus").innerHTML  = '<span class="txt-red-b-s" ><img src="../../images/no.gif"  /> <br/>Serial No. ไม่ถูกต้อง กรุณาแสกนข้อมูลใหม่</span>';
							}else{
								document.getElementById("model").select();
								document.getElementById("txtStatus").innerHTML   = '<span class="txt-red-b-s" ><img src="../../images/no.gif"  /><br/>ข้อมูลไม่ถูกต้อง กรุณาแสกนข้อมูลใหม่</span>';
								}//if(rqrTxt !="No"){
						}
					};
				// xmlhttp.send();
				 xmlhttp.send(null)
				
				
			}else{
		
				if (document.getElementById("model").value.length < 16 ){
					
					document.getElementById("model").select();
					document.getElementById("txtStatus").innerHTML  = '<span class="txt-red-b-s" ><img src="../../images/no.gif"  /><br/>ข้อมูลไม่ถูกต้อง กรุณาแสกนข้อมูลใหม่</span>';
				}else{
				//alert(sModel.length);
				
					
				//S-------------------------------------------------------------------------------------------	
					if (window.XMLHttpRequest) {
						// code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} else {
						// code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.open("GET","chklabel.php?qcmodel="+subModel+"&trmodel="+tmodel+"&tgno="+sTag+"&modelsr="+sModel,true); 
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						  // document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
							var qrTxt= xmlhttp.responseText;
							var rqrTxt= qrTxt.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '')
							//alert(rqrTxt);
							switch(rqrTxt) {
									case "Wrong":
										document.getElementById("model").select();
										document.getElementById("txtStatus").innerHTML  = '<span class="txt-red-b-s" ><img src="../../images/no.gif"  /><br/>Serial No. ไม่ถูกต้อง กรุณาแสกนข้อมูลใหม่</span>';
										break;
									case "No":
										document.getElementById("model").select();
										document.getElementById("txtStatus").innerHTML  = '<span class="txt-red-b-s" ><img src="../../images/no.gif"  /><br/>ข้อมูลโมเดลที่แสกนไม่ตรงกับในระบบ กรุณาแสกนโมเดลที่ถูกต้อง</span>';
										break;
									case "1":
										document.getElementById("txtStatus").innerHTML  = '<span class="txt-green-b-s" ><img src="../../images/yes.gif"  /><br/>ข้อมูลถูกต้อง ระบบกำลังบันทึกข้อมูล...</span>';//Please scan model no.
										document.getElementById("scan").submit();
										break;
									default:
										document.getElementById("model").select();
										document.getElementById("txtStatus").innerHTML  = '<span class="txt-red-b-s" ><img src="../../images/no.gif"  /><br/>ข้อมูลไม่ถูกต้อง กรุณาแสกนข้อมูลใหม่</span>';
										break;
								} //	switch(rqrTxt) {
						}
						
					};
				// xmlhttp.send();
				 xmlhttp.send(null)
				
				
				//E-------------------------------------------------------------------------------------------		
				
				}//	if (document.getElementById("model").value.length < 16 ){
			}//if(sModel.substring(0, 1) == "EW"){
				
		}//if (sModel == ""){
}// page print

/*EndReal time printing - printtag.php*/

/*-------------Start Real time printing - fgprint_tag.php---------------------------*/
function validateSpTag(scan) {
	var sTag=document.getElementById("model").value;
	var sTmodel=document.getElementById("nmodel").value;
	
	//alert(sTmodel);
	
	if (sTag == ""){
		//alert ("Please scan model no.");
		document.getElementById("model").focus();
		document.getElementById("txtStatus").innerHTML  ='<span class="txt-red-b-s" >กรุณาแสกน FG Tag No.</span>';//Please scan model no.
		return false;
		}else{
			
			if(sTag.length >="9"){
			
			//S-------------------------------------------------------------------------------------------	
				if (window.XMLHttpRequest) {
					// code for IE7+, Firefox, Chrome, Opera, Safari

					xmlhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.open("GET","chkTag.php?qctag="+sTag+"&tsmodel="+sTmodel,true);
				xmlhttp.onreadystatechange = function() {
					
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					  // document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
						var qrTxt= xmlhttp.responseText;
						var rqrTxt= qrTxt.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '')
						//alert(rqrTxt);
						if(rqrTxt !="No"){
					//	alert(sModel.length);
						document.getElementById("txtStatus").innerHTML  = '<span class="txt-green-b-s" ><img src="../../images/yes.gif"  /><br/>ข้อมูลถูกต้อง ระบบกำลังบันทึกข้อมูล...</span>';//Please scan model no.
						//alert(document.getElementById("hslid").value);green
						document.getElementById("scan").submit();
						
						}else{
							//alert("Please input model No.(15 Digit)")
							document.getElementById("model").select();
							document.getElementById("txtStatus").innerHTML  ='<span class="txt-red-b-s" > Tag ถูกปริ้นไปแล้ว หรือ ' + '<br/>' + 'โมเดลของ Tag ที่แสกนไม่ตรงกับ โมเดลที่เลือก กรุณาแสกน Tag No. ที่ถูกต้อง</span>';
						}//if(rqrTxt !="No"){
		
					}
					
				};
			// xmlhttp.send();
			 xmlhttp.send(null)
			
			
			//E-------------------------------------------------------------------------------------------		
	
			}else{
					//alert("Please input model No.(15 Digit)")
					document.getElementById("model").select();
					document.getElementById("txtStatus").innerHTML  ='<span class="txt-red-b-s" > ไม่พบ Tag No. ในระบบ กรุณาแสกนข้อมูลที่ถูกต้อง</span>';
					return false ;
					
					}//if(str.length=15){
				
		}
		
}// page print
/*------------EndReal time printing - fgprint_tag.php---------------------------------*/

/*-------------Start Real time printing Check line - line.php---------------------------*/
function validateLine(scan) {
	var sLine=document.getElementById("line").value;
	//alert(sModel);
	if (sLine == ""){
		//alert ("Please scan model no.");
		document.getElementById("line").focus();
		document.getElementById("txtStatus").innerHTML  ='<span class="txt-red-b-s" >กรุณาแสกนชื่อไลน์</span>';
		return false;
		}else{

			//S-------------------------------------------------------------------------------------------	
				if (window.XMLHttpRequest) {
					// code for IE7+, Firefox, Chrome, Opera, Safari
					
					xmlhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.open("GET","chkline.php?gline="+sLine,true);
				xmlhttp.onreadystatechange = function() {
					
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					  // document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
						var qrTxt= xmlhttp.responseText;
						var rqrTxt= qrTxt.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '')
						//alert(rqrTxt);
						if(rqrTxt !="No"){
					//	alert(sModel.length);
						document.getElementById("txtStatus").innerHTML  ='<span class="txt-green-b-s" >กำลังเข้าสู่ระบบ...</span>';//Please scan model no.
						//alert(document.getElementById("hslid").value);
						document.getElementById("scan").submit();
						
						}else{
							//alert("Please input model No.(15 Digit)")
							document.getElementById("line").select();
							document.getElementById("txtStatus").innerHTML  ='<span class="txt-red-b-s" >ไม่พบข้อมูลไลน์ผลิต กรุณาแสกนชื่อไลน์ที่ถูกต้อง</span>';
						}//if(rqrTxt !="No"){
		
					}
					
				};
			// xmlhttp.send();
			 xmlhttp.send(null)
			
			
			//E-------------------------------------------------------------------------------------------		

				
		}
		
}// page print
/*------------EndReal Real time printing Check line - line.php---------------------------*/



function ConfirmRept(){
			if 	 (confirm ("Do you want to reprint Tag?")==true){ 
				return true;
			}
				return false;
		}
	