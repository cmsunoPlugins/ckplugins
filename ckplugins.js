//
// CMSUno
// Plugin CKplugins
//
function f_add_ckplugins(e){
	let z=document.getElementById('ckpluginsZip').value;
	if(z.substr(z.length-3).toLowerCase()!='zip')f_alert('!'+e);
	else{
		let x=new FormData();
		x.set('action','add');
		x.set('unox',Unox);
		x.set('z',z);
		fetch('uno/plugins/ckplugins/ckplugins.php',{method:'post',body:x})
		.then(r=>r.text())
		.then(function(r){
			f_alert(r);
			f_load_ckplugins();
		});
	}
}
//
function f_save_ckplugins(){
	let c=document.getElementById('ckpluginsCommand').value,x=new FormData();
	x.set('action','save');
	x.set('unox',Unox);
	x.set('c',c);
	fetch('uno/plugins/ckplugins/ckplugins.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(r=>f_alert(r));
}
//
function f_del_ckplugins(f){
	let x=new FormData();
	x.set('action','del');
	x.set('unox',Unox);
	x.set('p',f);
	fetch('uno/plugins/ckplugins/ckplugins.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(function(r){
		f_alert(r);
		f_load_ckplugins();
	});
}
//
function f_load_ckplugins(){
	document.getElementById('ckpluginsCur').innerHTML='';
	fetch("uno/data/ckplugins.json?r="+Math.random())
	.then(r=>r.json())
	.then(function(data){
		document.getElementById('ckpluginsCommand').value=data.conf;
		data.ckplug.forEach(function(v){
			document.getElementById('ckpluginsCur').insertAdjacentHTML('beforeend','<tr><td style="width:100px;vertical-align:middle;padding-left:40px;text-transform:capitalize;">'+v+'</td><td width="30px" style="cursor:pointer;background:transparent url(\''+Udep+'includes/img/close.png\') no-repeat scroll center center;" onClick="f_del_ckplugins(\''+v+'\');"></td></tr>');
		});
	});
}
//
f_load_ckplugins();
