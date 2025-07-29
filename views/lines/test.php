<?php 

  define('SSO', 'sso/');
  define('DIR_PAGE', 'prod/manage_fgtag_line/'); //manage_fgtag
  define('DIR_SRV_PAGE', 'prod/manage_fgtag/'); //manage_fgtag
  define('DIR_PARTH', '/htdocs/prod/manage_fgtag_line/');  //www/prod/manage_fgtag/


   define('SRV_SHARED', '10.164.213.32'); //SERVER SHARED FILE FOR BARTENDER

  define('DIR_IMAGES', 'images/');
  define('DIR_CSS', 'css/');
  define('DIR_INCLUDES', 'includes/');
  define('DIR_FUNCTIONS', 'functions/');
  define('DIR_JAVA', 'javascript/');
  define('DIR_VIEWS', 'views/');
  define('DIR_MENU', 'menu/');
  define('DIR_UPLOAD', 'uploads/');
  define('DIR_BTW', 'btw/');
  define('DIR_MPIC', 'picmodel/');
  define('DIR_LINE', 'lines/');
  define('DIR_PRINT', 'print/'); 
  $srv=SRV_SHARED;
  $srv_template = "eticket_template";
  $idline = '40';

  
//echo $fp = fopen("../".DIR_VIEWS.DIR_UPLOAD.DIR_PRINT.$idline.".txt", "w") or die("Can't Create file, Please contact Administrator.");

//echo $flgCopyDT = copy("../".DIR_VIEWS.DIR_UPLOAD.DIR_PRINT.$idline.".txt", "\\..\\..\\..\\..\\..\\test_tag\\print\\$idline\\fgtag.txt");

//echo $flgCopy1 = copy("\\..\\..\\..\\..\\..\\test_tag\\print\\$idline\\cmd.txt", "\\..\\..\\..\\..\\..\\test_tag\\print\\$idline\\cmdb\\cmd.txt");

//echo $flgCopy2 = copy("\\..\\..\\..\\..\\..\\test_tag\\print\\$idline\\cmd.txt", "\\..\\..\\..\\..\\..\\test_tag\\print\\$idline\\cmds\\cmd.txt");

//echo $flgCopyDT = copy("30.txt", "\\\\$srv\\$srv_template\\print\\$idline\\fgtag.txt");
									
//echo $flgCopy1 = copy("\\\\$srv\\$srv_template\\print\\$idline\\cmd.txt", "\\\\$srv\\$srv_template\\print\\$idline\\cmdb\\cmd.txt");
									
echo $flgCopy2 = copy("\\\\$srv\\$srv_template\\print\\$idline\\cmd.txt", "\\\\$srv\\$srv_template\\print\\$idline\\cmd2.txt");

if($flgCopy2) {
echo "transfer done";
} else {
echo "faild";
}

//$fp = fopen("../uploads/print/36.txt", "w");

$fp = fopen("../".DIR_UPLOAD.DIR_PRINT.$idline.".txt", "w");
							
								//$fp = fopen("\\\\$srv\\test_tag\\uploads\\$idline\\fgtag.txt","w") or die("Can't Create file, Please contact Administrator.");
									$i=1;
							fwrite($fp,"Model,Tag No,Shift,Model Name,Model No,Produce Date ,Qty ,Serial No 1-n,Printed by,Printed date,Serial 1,Serial 2,Serial 3,Serial 4,Serial 5,Serial 6,Serial 7,Serial 8,Serial 9,Serial 10,Serial 11,Serial 12,Serial 13,Serial 14,Serial 15,Serial 16,FgTag,Part No,Part Name,image,status print,fg print,stdQty,datereprint,ticket_no,ticket_bcode,Leader,Floater,Printer\r\n");
							fwrite($fp,$rstg['model_code'].",".$rstg['tag_no'].",".$rstg['shift'].",".$rstg['model_name'].",".$rstg['tag_model_no'].",".$rstg['dateprint'].",".$rstg['tag_qty'].",".$rstg['allserial'].",".$rstg['line_name'].",".$rstg['dateprint'].$serialtxt.$rstg['fg_tag_barcode'].",".$rstg['customer_part_no'].",".$rstg['customer_part_name'].",".$rstg['model_picture'].",".$rstg['status_print'].",,".$rstg['std_qty'].",".$rstg['datereprint'].",".$mtkno9.",".$tk_bcode.",".$rstg['leadern'].",".$rstg['floatern'].",".$rstg['printern']."\r\n");
							fclose($fp);

//echo date("Y-m-d H:i:s");

?>