<?php
session_start(); 
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {sleep(2);exit;} // ajax request
if(!isset($_POST['unox']) || $_POST['unox']!=$_SESSION['unox']) {sleep(2);exit;} // appel depuis uno.php
?>
<?php
include('../../config.php');
include('lang/lang.php');
if(!file_exists('../../data/ckplugins.json')) file_put_contents('../../data/ckplugins.json', '{"ckplug":[],"conf":""}');
// ********************* actions *************************************************************************
if (isset($_POST['action']))
	{
	switch ($_POST['action'])
		{
		// ********************************************************************************************
		case 'plugin': ?>
		<div class="blocForm">
			<h2><?php echo T_("CKplugins");?></h2>
			<p><?php echo T_("This plugin allows you to add new features / buttons to the editor.");?></p>
			<p><?php echo T_("You just need to upload your CKEditor plugin with the form bellow (Zip file).");?></p>
			<h3><?php echo T_("Add a CKEditor plugin");?> :</h3>
			<table class="hForm">
				<tr>
					<td><label><?php echo T_("CKEditor plugin (zip)");?></label></td>
					<td style="max-width:420px;">
						<input type="text" class="input" style="max-width:170px;" name="ckpluginsZip" id="ckpluginsZip" value="" />
						<div class="bouton" style="margin-left:30px;" id="ckpluginsBF" onClick="f_finder_select('ckpluginsZip')" title="<?php echo T_("File manager");?>"><?php echo T_("File Manager");?></div>
						<div class="bouton fr" onClick="f_add_ckplugins('<?php echo T_("Zip file needed");?>');" title="<?php echo T_("Add plugin");?>"><?php echo T_("Add");?></div>
					</td>
					<td><em><?php echo T_("Upload Zip file with finder and select it.");?></em></td>
				</tr>
				<tr>
					<td style="vertical-align:middle"><label><?php echo T_("CKEditor special config");?></label></td>
					<td>
						<textarea name="ckpluginsCommand" id="ckpluginsCommand" style="width:100%;"></textarea>
					</td>
					<td style="vertical-align:middle"><em><?php echo T_("Add special command but not config.extraConfig.");?></em></td>
				</tr>
			</table>
			<div class="bouton fr" onClick="f_save_ckplugins();" title="<?php echo T_("Save config");?>"><?php echo T_("Save config");?></div>
			<div class="clear"></div>
			<h3><?php echo T_("Existing plugins :");?></h3>
			<form id="frmBox">
				<table id="ckpluginsCur"></table>
			</form>
			<div class="clear"></div>
		</div>
		<?php break;
		// ********************************************************************************************
		case 'add':
		if(!is_dir('../../data/ckplugins/')) mkdir('../../data/ckplugins/');
		if(!is_dir('../../data/ckplugins/tmp/')) mkdir('../../data/ckplugins/tmp/');
		$q = file_get_contents('../../data/ckplugins.json');
		$a = json_decode($q,true);
		$zip = new ZipArchive;
		$f = $zip->open('../../..'.$_POST['z']);
		if($f===true)
			{
			$zip->extractTo('../../data/ckplugins/tmp/');
			$p = findFile('plugin.js','../../data/ckplugins/tmp/');
			if($p)
				{
				$n = basename(dirname($p));
				copyDir(dirname($p),'../../data/ckplugins/'.$n.'/',$p=0755);
				rmdirR('../../data/ckplugins/tmp/');
				if(!in_array($n,$a['ckplug'])) $a['ckplug'][] = $n;
				if(file_put_contents('../../data/ckplugins.json', json_encode($a))) echo $n.' '.T_('Installed');
				else echo '!'.T_('Error');
				}
			$zip->close();
			}
		else echo '!'.T_('Error');
		break;
		// ********************************************************************************************
		case 'save':
		$q = file_get_contents('../../data/ckplugins.json');
		$a = json_decode($q,true);
		$a['conf'] = $_POST['c'];
		$b = file_get_contents('ckpluginsCkeditorRef.js');
		$b = str_replace('[[ckplugconf]]',$a['conf'],$b);
		if(file_put_contents('../../data/ckplugins.json', json_encode($a)) && file_put_contents('ckpluginsCkeditor.js', $b)) echo T_('Saved');
		else echo '!'.T_('Error');
		break;
		// ********************************************************************************************
		case 'del':
		$q = file_get_contents('../../data/ckplugins.json');
		$a = json_decode($q,true);
		if(in_array($_POST['p'],$a['ckplug']))
			{
			$k = array_search($_POST['p'],$a['ckplug']);
			unset($a['ckplug'][$k]);
			$a['ckplug'] = array_values($a['ckplug']);
			}
		rmdirR('../../data/ckplugins/'.$_POST['p'].'/');
		if(file_put_contents('../../data/ckplugins.json', json_encode($a))) echo T_('Plugin removed');
		else echo '!'.T_('Error');
		break;
		// ********************************************************************************************
		}
	clearstatcache();
	exit;
	}
//
function findFile($f,$d)
	{
	if(substr($d,-1,1)!="/") $d.="/";
	if(is_dir($d))
		{
		$dh = opendir($d);
		while($fn=readdir($dh))
			{
			if(is_file($d.$fn) && $fn==$f)
				{
				closedir($dh);
				return $d.$fn;
				}
			if($fn!="." && $fn!=".." && is_dir($d.$fn))
				{
				$r = findFile($f,$d.$fn);
				if($r)
					{
					closedir($dh);
					return $r;
					}
				}
			}
		closedir($dh);
		}
	return false;
    }
//
function copyDir($s,$d,$p=0755)
	{
	if(is_link($s)) return symlink(readlink($s), $d);
	if(is_file($s)) return copy($s, $d);
	if(!is_dir($d)) mkdir($d, $p);
	$dir = dir($s);
	while(false!==$e=$dir->read())
		{
		if($e=='.'||$e=='..') continue;
		copyDir($s.'/'.$e, $d.'/'.$e, $p);
		}
	$dir->close();
	return true;
	}
//
function rmdirR($d)
	{
	$files = array_diff(scandir($d), array('.','..'));
	foreach($files as $f)
		{
		(is_dir("$d/$f")) ? rmdirR("$d/$f") : unlink("$d/$f");
		}
	return rmdir($d);
	}
//
?>
