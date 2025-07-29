#configure
configure.php for line  &  bsi page  (** Config will be diffarent on serser for BSI)

configure_srv.php for line connect to server

configure_host.php for server,  FG

#template
template_top for Server manager
template_top_fg for FG printing supplier Tag

template_top_line for select Line => not login yet 
template_topl for Final Line => after login 
template_top_bsi for BSI Line => after login 


#LINK 
BSI => http://linuxapps.fttl.ten.fujitsu.com/prod/manage_fgtag/views/lines/index_line.php
Final => http://localhost/prod/manage_fgtag/views/lines/index_line.php
FG => http://"Localhost IP "/prod/manage_fgtag/index_fg.php
Manage =>http://linuxapps.fttl.ten.fujitsu.com/prod/manage_fgtag/

*File chkline.php in line Localhost system are diffarent with Server
 because only BSI can login on server and only Final line can login Localhost. 

