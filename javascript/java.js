// JavaScript Document
///////////////////////////////////////เปิดpop-up
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
///////////////////////////////////////ปิดเปิดpop-up
//////////////////////////ค่าว่าง form ขื่อfrom ต้องเป็น chk เท่านั้น
function do_submit(){
    for (var i = 0; i < frm.elements.length; i++) {
		var chk = frm.elements[i];
        if(chk.type == "text" && chk.value == ""){
			alert( 'กรุณากรอกข้อความให้ครบถ้วน' );
      		frm.elements[i].focus();

            return false;
        }
		
		if(chk.type == "textarea" && chk.value == ""){
		
		alert( 'กรุณากรอกข้อความให้ครบถ้วน' );
      		frm.elements[i].focus();

            return false;
		
		}
		
    }
    return confirm('ยืนยันข้อมูล !')?true:false; 
}
//////////////////////////ปิดค่าว่าง form
//////////////////////////ค่าว่าnew////////////
 function chk_form(){  
     $(":input + span.require").remove();  
     $(":input").each(function(){  
         $(this).each(function(){      
             if($(this).val()==""){  
                 $(this).after("<span class=require><<จำเป็นต้องกรอก</span>");  
             }  
         });  
     });  
     if($(":input").next().is(".require")==false){  
         return true;  
     }else{  
         return false;  
     }  
 } 

/////////////////////////////////////////จังหวัด
function Inint_AJAX()
{
	try
	{
		return new ActiveXObject( "Msxml2.XMLHTTP" );
	}
	catch ( e )
	{
	};

	try
	{
		return new ActiveXObject( "Microsoft.XMLHTTP" );
	}
	catch ( e )
	{
	};

	try
	{
		return new XMLHttpRequest();
	}
	catch ( e )
	{
	};

	alert( "XMLHttpRequest not supported" );
	return null;
};

function dochange( obj )
{
	var req = Inint_AJAX();
	var province = document.getElementById( 'province' ).value;
	var tumbon = document.getElementById( 'tumbon' ).value;
	if ( obj && obj.name == 'province' ) //เมื่อทำการเลือที่จังหวัดมา ให้เคลียร์ค่าอำเภอ
	{
		var amphur = "";
	}
	else //เลือกรายการอื่น
	{
		var amphur = document.getElementById( 'amphur' ).value;
	};
	var data = "province=" + province + "&amphur=" + amphur + "&tumbon=" + tumbon;
	req.onreadystatechange = function()
	{
		if ( req.readyState == 4 )
		{
			if ( req.status == 200 )
			{
				var datas = eval( '(' + req.responseText + ')' ); // JSON
				document.getElementById( 'provinceDiv' ).innerHTML = datas[0].province;
				document.getElementById( 'amphurDiv' ).innerHTML = datas[0].amphur;
				document.getElementById( 'tumbonDiv' ).innerHTML = datas[0].tumbon;
			};
		};
	};
	req.open( "post" , "province.php" , true ); //สร้าง connection
	req.setRequestHeader( "Content-Type", "application/x-www-form-urlencoded" ); // set Header
	req.send( data ); //ส่งค่า
};
//////////////////////////////////////////////
/////////chk member////////////
function chk_mem() {
	//document.getElementById("table_1").style.display = "";
	var URL = 'chk_mem_true.php';
	var user = document.getElementById("user").value;
	var data = "act=add&user="+ user  ;
	ajaxLoad('get', URL, data, 'table_1');
}

/////////chk member////////////

//////////chk pass///////////////
function testPassword(passwd)
{
var intPassWd = 0
var strVerdict = "weak"
var strLog = ""
// PASSWORD LENGTH
if (passwd.length<1) // ความยาวของรหัสผ่าน
{
intPassWd = (intPassWd+0)
strLog = strLog + "1 คะแนนสำหรับความยาวนี้ (" + passwd.length + ")\n"
}else if (passwd.length>1 && passwd.length<5) // ความยาวของรหัสผ่านระหว่าง 2 - 5
{
intPassWd = (intPassWd+1)
strLog = strLog + "3 คะแนนสำหรับความยาวนี้ (" + passwd.length + ")\n"
}
else if (passwd.length>4 && passwd.length<8) // ความยาวของรหัสผ่านระหว่าง 5 - 7
{
intPassWd = (intPassWd+2)
strLog = strLog + "3 คะแนนสำหรับความยาวนี้ (" + passwd.length + ")\n"
}
else if (passwd.length>7 && passwd.length<16)// ความยาวของรหัสผ่านระหว่าง 8 - 15
{
intPassWd = (intPassWd+5)
strLog = strLog + "6 คะแนนสำหรับความยาวนี้ (" + passwd.length + ")\n"
}
else if (passwd.length>15) // ความยาวของรหัสผ่านมากว่า 16
{
intPassWd = (intPassWd+8)
strLog = strLog + "9 คะแนนสำหรับความยาวนี้ (" + passwd.length + ")\n"
}
// ให้คะแนนความปลอดภัยเพิ่มเติม
if (passwd.match(/[a-z]/)) //ถ้ามี a-z
{
intPassWd = (intPassWd+1)
strLog = strLog + "1 คะแนนสำหรับเงื่่อนไขนี้\n"
}
if (passwd.match(/[A-Z]/)) //ถ้ามี A-Z
{
intPassWd = (intPassWd+1)
strLog = strLog + "1 คะแนนสำหรับเงื่่อนไขนี้\n"
}
// NUMBERS
if (passwd.match(/\d+/)) // ถ้ามีตัวเลข
{
intPassWd = (intPassWd+1)
strLog = strLog + "5 คะแนนสำหรับเงื่่อนไขนี้\n"
}
if (passwd.match(/(.*[0-9].*[0-9].*[0-9])/)) // ถ้ามีตัวเลข ต่อท้าย 3 ตัว
{
intPassWd = (intPassWd+3)
strLog = strLog + "3 คะแนนสำหรับเงื่่อนไขนี้\n"
}
// SPECIAL CHAR
if (passwd.match(/.[!,@,#,$,%,^,&,*,?,_,~]/)) // ถ้ามีตัวอักษรพิเศษ 1 ตัว
{
intPassWd = (intPassWd+4)
strLog = strLog + "5 คะแนนสำหรับเงื่่อนไขนี้\n"
}
// [verified] at least two special characters
if (passwd.match(/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/))
{
intPassWd = (intPassWd+4)
strLog = strLog + "5 คะแนนสำหรับเงื่่อนไขนี้\n"
}


// COMBOS
if (passwd.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) // ถ้ามีตัวอักษรตัวเล็กตัวใหญ่สลับกัน
{
intPassWd = (intPassWd+5)
strLog = strLog + "3 คะแนนสำหรับเงื่่อนไขนี้\n"
}

if (passwd.match(/([a-zA-Z])/) && passwd.match(/([0-9])/)) // ถ้ามีตัวอักษรเ และเลข
{
intPassWd = (intPassWd+6)
strLog = strLog + "4 คะแนนสำหรับเงื่่อนไขนี้\n"
}

// ถ้ามีตัวอักษรเ และเลข และตัวอักษรพิเศษ
if (passwd.match(/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[a-zA-Z0-9])/))
{
intPassWd = (intPassWd+7)
strLog = strLog + "7 คะแนนสำหรับเงื่่อนไขนี้\n"
}


if(intPassWd <= 4)
{
strVerdict = "<font color='#FF0000'>ปลอดภัยน้อยมาก</font>"
strBg="#FF0000"
}
else if (intPassWd >=5 && intPassWd <= 9)
{
strVerdict = "<font color='#FF6600'>ปลอดภัยน้อย</font>"
strBg="#FF6600"
}
else if (intPassWd >= 10 && intPassWd <= 14)
{
strVerdict = "<font color='#FFe000'>ปลอดภัย</font>"
strBg="#FFFF00"
}
else if (intPassWd >= 10 && intPassWd <= 19)
{
strVerdict = "<font color='#00CC00'>ปลอดภัยมาก</font>"
strBg="#00CC00"
}
else
{
strVerdict = "<font color='#009900'>ปลอดภัยมากที่สุด</font>"
strBg="#009900"
}
var topwidth=20;
var curscor=parseInt((intPassWd*220)/topwidth);
document.getElementById("strengprogress").style.backgroundColor=strBg
document.getElementById("strengprogress").style.width=(curscor)+"px"
document.getElementById("strengprogress").style.height="1px"
document.getElementById("verdict").innerHTML=(strVerdict)
document.getElementById("test").innerHTML=intPassWd
}
//////////chk pass//////////////
/////////chk email////////////
function chk_email(){
	//document.getElementById("table_1").style.display = "";
	var URL = 'chk_email_true.php';
	var user = document.getElementById("email1").value;
	var data = "act=add&email="+ email1 ;
	ajaxLoad('get', URL, data, 'table_2');
}

/////////chk email////////////


///////////////////////   num only
  
      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }

////////////

/////////////// search date

 


