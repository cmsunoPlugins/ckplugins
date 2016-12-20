//
// CMSUno
// Plugin CKplugins
//
var ckplug=[],ckplugconf;
jQuery(document).ready(function(){
	jQuery.getJSON("uno/data/ckplugins.json?r="+Math.random(),function(data){
		ckplugconf=data.conf;
		jQuery.each(data.ckplug,function(k,d){ckplug[k]=d;});
	});
});
