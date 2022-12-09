//
// CMSUno
// Plugin CKplugins
//
var ckplug=[],ckplugconf;
fetch("uno/data/ckplugins.json?r="+Math.random())
.then(r=>r.json())
.then(function(data){
	ckplugconf=data.conf;
	data.ckplug.forEach(function(v,k){ckplug[k]=v;});
});
