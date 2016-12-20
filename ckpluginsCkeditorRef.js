//
// CMSUno
// Plugin CKplugins
//
UconfigNum++;
var ckplugextra='',i;
for(i=0;i<ckplug.length;++i){
	CKEDITOR.plugins.addExternal(ckplug[i],'../../../data/ckplugins/'+ckplug[i]+'/');
	ckplugextra+=','+ckplug[i];
}
CKEDITOR.editorConfig=function(config){
	if(ckplugextra!='')config.extraPlugins+=ckplugextra;
	[[ckplugconf]]
	if(UconfigFile.length>UconfigNum)config.customConfig=UconfigFile[UconfigNum];
};
