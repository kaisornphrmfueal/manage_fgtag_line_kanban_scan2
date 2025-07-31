	<div class="menu">
		<ul id="nav" class="dropdown dropdown-horizontal">
    
           <!--  <li><a href="index.php?id=<?=base64_encode('line')?>">Select Line</a></li>--!-->
            <li><a href="index.php?id=<?=base64_encode('print')?>">Print Tag</a></li>
             <li><a href="index.php?id=<?=base64_encode('reprint')?>">Reprint Tag</a></li>
             
             <li><span class="dir">Tag Report</span>
				<ul>
					   <li><a href="index.php?id=<?=base64_encode('line_report')?>">Tag Report</a></li>
                       <li><a href="index.php?id=<?=base64_encode('tag_sum')?>">Tag Summary</a></li>
			   </ul>
		 	</li>
			 <li><a href="index.php?id=<?=base64_encode('model_list')?>">Model List Report</a></li>
             <li><a href="index.php?id=<?=base64_encode('operator')?>">Operator</a></li>
             <li><span class="dir">Adjust Data</span>
				<ul>
					   <!--<li><a href="index.php?id=<?=base64_encode('adjust_model_scan')?>">Adjust Tag Data</a></li>
                       <li><a href="index.php?id=<?=base64_encode('report_max_tag')?>">Report Max Serial</a></li>
                       <li><a href="index.php?id=<?=base64_encode('adjust_report')?>">Report for Adjust</a></li>-->
                       
                       <li><a href="windows.php?win=confirm&page=adjust_model_scan">Adjust Tag Data</a></li>
                       <li><a href="windows.php?win=confirm&page=adjust_report">Report for Adjust</a></li>
                       <li><a href="windows.php?win=confirm&page=report_max_tag">Report Max Serial</a></li>
					   <li><a href="../../includes/add-on/cancel_tag/cancel_tag.php">Cancel FGTAG</a></li>
                       
			   </ul>
		 	</li>
			<!-- START Tai Comment
            <li><span class="dir">Upload Data</span>
				<ul>
					   <li><a href="index.php?id=<?=base64_encode('upload_tag')?>">Upload Data to Server</a></li>
                       <li><a href="index.php?id=<?=base64_encode('upload_tag_report')?>">Report Upload Data</a></li>
			   </ul>
		 	</li>
			
             <li><span class="dir">Update Master Data</span>
				<ul>
					   <li><a href="index.php?id=<?=base64_encode('update_master_data')?>">Update Master Data</a></li>
                       <li><a href="index.php?id=<?=base64_encode('update_master_data_report')?>">History Update Master Data</a></li>
			   </ul>
		 	</li>
			
			 END Tai Comment -->
            <li><a href="../wi/fgTagLine.pdf" target="_blank">WI</a></li> 
		</ul>
	</div>
    
    
    