//
// CMSUno
// Plugin CKplugins
//
function f_add_ckplugins(e){
	jQuery(document).ready(function(){
		var z=document.getElementById('ckpluginsZip').value;
		if(z.substr(z.length-3).toLowerCase()!='zip')f_alert('!'+e);
		else jQuery.post('uno/plugins/ckplugins/ckplugins.php',{'action':'add','unox':Unox,'z':z},function(r){
			f_alert(r);
			f_load_ckplugins();
		});
	});
}
//
function f_save_ckplugins(){
	jQuery(document).ready(function(){
		var c=document.getElementById('ckpluginsCommand').value;
		jQuery.post('uno/plugins/ckplugins/ckplugins.php',{'action':'save','unox':Unox,'c':c},function(r){
			f_alert(r);
		});
	});
}
//
function f_del_ckplugins(f){
	jQuery(document).ready(function(){
		jQuery.post('uno/plugins/ckplugins/ckplugins.php',{'action':'del','unox':Unox,'p':f},function(r){
			f_alert(r);
			f_load_ckplugins();
		});
	});
}
//
function f_load_ckplugins(){
	jQuery(document).ready(function(){
		jQuery('#ckpluginsCur').empty();
		jQuery.getJSON("uno/data/ckplugins.json?r="+Math.random(),function(data){
			document.getElementById('ckpluginsCommand').value=data.conf;
			jQuery.each(data.ckplug,function(k,d){
				jQuery('#ckpluginsCur').append('<tr><td style="width:100px;vertical-align:middle;padding-left:40px;text-transform:capitalize;">'+d+'</td><td width="30px" style="cursor:pointer;background:transparent url(\''+Udep+'includes/img/close.png\') no-repeat scroll center center;" onClick="f_del_ckplugins(\''+d+'\');"></td></tr>');
			});
		});
	});
}
//
f_load_ckplugins();
