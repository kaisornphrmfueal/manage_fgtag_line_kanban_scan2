// JavaScript Document
///////////////////////////////////////�Դpop-up
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
///////////////////////////////////////�Դ�Դpop-up
//////////////////////////�����ҧ form ����from ��ͧ�� chk ��ҹ��
function do_submit(){
    for (var i = 0; i < frm.elements.length; i++) {
		var chk = frm.elements[i];
        if(chk.type == "text" && chk.value == ""){
			alert( '��سҡ�͡��ͤ������ú��ǹ' );
      		frm.elements[i].focus();

            return false;
        }
		
		if(chk.type == "textarea" && chk.value == ""){
		
		alert( '��سҡ�͡��ͤ������ú��ǹ' );
      		frm.elements[i].focus();

            return false;
		
		}
		
    }
    return confirm('�׹�ѹ������ !')?true:false; 
}
//////////////////////////�Դ�����ҧ form
//////////////////////////������new////////////
 function chk_form(){  
     $(":input + span.require").remove();  
     $(":input").each(function(){  
         $(this).each(function(){      
             if($(this).val()==""){  
                 $(this).after("<span class=require><<���繵�ͧ��͡</span>");  
             }  
         });  
     });  
     if($(":input").next().is(".require")==false){  
         return true;  
     }else{  
         return false;  
     }  
 } 

/////////////////////////////////////////�ѧ��Ѵ
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
	if ( obj && obj.name == 'province' ) //����ͷӡ�����ͷ��ѧ��Ѵ�� ����������������
	{
		var amphur = "";
	}
	else //���͡��¡�����
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
	req.open( "post" , "province.php" , true ); //���ҧ connection
	req.setRequestHeader( "Content-Type", "application/x-www-form-urlencoded" ); // set Header
	req.send( data ); //�觤��
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
if (passwd.length<1) // ������Ǣͧ���ʼ�ҹ
{
intPassWd = (intPassWd+0)
strLog = strLog + "1 ��ṹ����Ѻ������ǹ�� (" + passwd.length + ")\n"
}else if (passwd.length>1 && passwd.length<5) // ������Ǣͧ���ʼ�ҹ�����ҧ 2 - 5
{
intPassWd = (intPassWd+1)
strLog = strLog + "3 ��ṹ����Ѻ������ǹ�� (" + passwd.length + ")\n"
}
else if (passwd.length>4 && passwd.length<8) // ������Ǣͧ���ʼ�ҹ�����ҧ 5 - 7
{
intPassWd = (intPassWd+2)
strLog = strLog + "3 ��ṹ����Ѻ������ǹ�� (" + passwd.length + ")\n"
}
else if (passwd.length>7 && passwd.length<16)// ������Ǣͧ���ʼ�ҹ�����ҧ 8 - 15
{
intPassWd = (intPassWd+5)
strLog = strLog + "6 ��ṹ����Ѻ������ǹ�� (" + passwd.length + ")\n"
}
else if (passwd.length>15) // ������Ǣͧ���ʼ�ҹ�ҡ��� 16
{
intPassWd = (intPassWd+8)
strLog = strLog + "9 ��ṹ����Ѻ������ǹ�� (" + passwd.length + ")\n"
}
// ����ṹ������ʹ����������
if (passwd.match(/[a-z]/)) //����� a-z
{
intPassWd = (intPassWd+1)
strLog = strLog + "1 ��ṹ����Ѻ����͹䢹��\n"
}
if (passwd.match(/[A-Z]/)) //����� A-Z
{
intPassWd = (intPassWd+1)
strLog = strLog + "1 ��ṹ����Ѻ����͹䢹��\n"
}
// NUMBERS
if (passwd.match(/\d+/)) // ����յ���Ţ
{
intPassWd = (intPassWd+1)
strLog = strLog + "5 ��ṹ����Ѻ����͹䢹��\n"
}
if (passwd.match(/(.*[0-9].*[0-9].*[0-9])/)) // ����յ���Ţ ��ͷ��� 3 ���
{
intPassWd = (intPassWd+3)
strLog = strLog + "3 ��ṹ����Ѻ����͹䢹��\n"
}
// SPECIAL CHAR
if (passwd.match(/.[!,@,#,$,%,^,&,*,?,_,~]/)) // ����յ���ѡ�þ���� 1 ���
{
intPassWd = (intPassWd+4)
strLog = strLog + "5 ��ṹ����Ѻ����͹䢹��\n"
}
// [verified] at least two special characters
if (passwd.match(/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/))
{
intPassWd = (intPassWd+4)
strLog = strLog + "5 ��ṹ����Ѻ����͹䢹��\n"
}


// COMBOS
if (passwd.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) // ����յ���ѡ�õ����硵���˭���Ѻ�ѹ
{
intPassWd = (intPassWd+5)
strLog = strLog + "3 ��ṹ����Ѻ����͹䢹��\n"
}

if (passwd.match(/([a-zA-Z])/) && passwd.match(/([0-9])/)) // ����յ���ѡ��� ����Ţ
{
intPassWd = (intPassWd+6)
strLog = strLog + "4 ��ṹ����Ѻ����͹䢹��\n"
}

// ����յ���ѡ��� ����Ţ ��е���ѡ�þ����
if (passwd.match(/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[a-zA-Z0-9])/))
{
intPassWd = (intPassWd+7)
strLog = strLog + "7 ��ṹ����Ѻ����͹䢹��\n"
}


if(intPassWd <= 4)
{
strVerdict = "<font color='#FF0000'>��ʹ��¹����ҡ</font>"
strBg="#FF0000"
}
else if (intPassWd >=5 && intPassWd <= 9)
{
strVerdict = "<font color='#FF6600'>��ʹ��¹���</font>"
strBg="#FF6600"
}
else if (intPassWd >= 10 && intPassWd <= 14)
{
strVerdict = "<font color='#FFe000'>��ʹ���</font>"
strBg="#FFFF00"
}
else if (intPassWd >= 10 && intPassWd <= 19)
{
strVerdict = "<font color='#00CC00'>��ʹ����ҡ</font>"
strBg="#00CC00"
}
else
{
strVerdict = "<font color='#009900'>��ʹ����ҡ����ش</font>"
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

 


