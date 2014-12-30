<?php /**
* tempsvols.php Page principal du mod
* @package [MOD] Temp de Vol
* @author Snipe <santory@websantory.net>
* @version 0.2d
*	created		: 07/01/2007
*/

if (!defined('IN_SPYOGAME')) die("Hacking attempt");

require_once("views/page_header.php");

$query = "SELECT `active` FROM `".TABLE_MOD."` WHERE `action`='tempsvols' AND `active`='1' LIMIT 1";
if (!$db->sql_numrows($db->sql_query($query))) die("Hacking attempt");
// a modifier
	$req1 = "SELECT * FROM ".TABLE_USER_TECHNOLOGY." WHERE user_id='".$user_data['user_id']."'";
	$result1 = $db->sql_query($req1);
	$fetch1 = $db->sql_fetch_assoc($result1);
	$pub_RC = $fetch1['RC'];
	$pub_RI = $fetch1['RI'];
	$pub_PH = $fetch1['PH'];

	$req2 = "SELECT `planet_name` , `coordinates` FROM ".TABLE_USER_BUILDING." WHERE user_id='".$user_data['user_id']."'";
	$result2 = $db->sql_query($req2);
	$option = "<option value='choix'>Choisir une Plan&egrave;te</option>";
	while($fetch2 = $db->sql_fetch_assoc($result2)){
		$option .= "<option value='".$fetch2['coordinates']."'>".$fetch2['planet_name']." [".$fetch2['coordinates']."]</option>";
	}

?>


<!-- = = = = = site content = = = = = -->
<style type="text/css">
.hr {
		 width: 100%;
		 margin-bottom: 10px;
		 padding-top: 1px;
		 font-weight: bold;
}

</style>
<script type="text/javascript">
var speeduni = '<?php echo $server_config['speed_uni'];?>';
var data2 = Array(
	//typ,ant,speed,speed2,verbr,lager)
	//    0  , 1, 2   , 3    , 4 , 5      )
	Array(201, 1, 5000, 10000, 10, 5000), //KT
	Array(202, 1, 7500, 0, 50, 25000),    //GT
	Array(203, 1, 12500, 0, 20, 50),      //LJ
	Array(204, 2, 10000, 0, 75, 100),     //SJ
	Array(205, 2, 15000, 0, 300, 800),    //KRZ
	Array(206, 3, 10000, 0, 500, 1500),   //SS
	Array(207, 2, 2500, 0, 1000, 7500),   //KS
	Array(208, 1, 2000, 0, 300, 20000),   //REC
	Array(209, 1, 100000000, 0, 1, 0),    //Spio
	Array(211, 2, 4000, 5000, 1000, 500), //Bomber
	Array(212, 3, 5000, 0, 1000, 2000),   //Zer
  Array(213, 3, 100, 0, 1, 1000000),     //TS 
	Array(214, 3, 10000, 0, 250, 750)     //TR
);

function dist() {
	return 5;
}
function speed() {
	return 18000;
}

function div(a, b) {
	return Math.floor(a / b);
}

function mod(a, b) {
	return a - Math.floor(a / b) * b; // YEAH ^^
}

function pc(s1) {
	return pointconvert(s1);
}

function pointconvert(s) {
	var sx = '';
	s = s + '';
	for (ipc = s.length; ipc >= 0; ipc--) {
		sx = s.charAt(ipc) + sx;
		if ((div(s.length - ipc,3) == (s.length - ipc) / 3) && (s.length != ipc) && (0 != ipc)) sx = '.' + sx;
	}
	return sx;
}

function retint(s) {
	var sxx = '';
	s = s + '';
	var sx = s.toUpperCase();
	for(ir = 0; ir < sx.length; ir++) {
	if(sx.charCodeAt(ir) >= 48 && sx.charCodeAt(ir) <= 57) {
			sxx = sxx + sx.charAt(ir);
		}
	}
	return sxx;
}

function chkint(id) {
	if(id.value != retint(id.value)) {
		id.value = retint(id.value);
	}
}

function chkval(id) {
	if (id.value == '') id.value = '0';
}
function chkval1(id) {
	if (id.value == '') id.value = '1';
}

function berechne_table(id) {
	//alert(id);
	id++;
	id--;
	var antrieb = Array(document.getElementById('vbt').value,  document.getElementById('imp').value,  document.getElementById('ha').value);
	var spd = 0;

//-- data2 array --//

	for (i = 0; i < data2.length; i++) {
		if ((data2[i][0] == id) || (id == -1)) {
			spd = data2[i][2] * (1 + antrieb[data2[i][1]-1] * data2[i][1] / 10); //normal
			if (data2[i][3] != 0) {//-exceptions-
				if ((data2[i][0] == 201) && (antrieb[1] > 4)) spd = data2[i][3] * (1 + antrieb[1] * 2 / 10); //kt neu
				if ((data2[i][0] == 211) && (antrieb[2] > 7)) spd = data2[i][3] * (1 + antrieb[2] * 3 / 10); //bomber neu
			}
			document.getElementById('s' + data2[i][0]).firstChild.nodeValue = pc(Math.round(spd));
			document.getElementById('l' + data2[i][0]).firstChild.nodeValue = pc(data2[i][5] * document.getElementById('i' + data2[i][0]).value);
		}
	}
	berechne()
}

function berechne() {
	var start = Array(document.getElementById('st_gal').value, document.getElementById('st_sys').value, document.getElementById('st_pla').value);
	var ziel  = Array(document.getElementById('zi_gal').value, document.getElementById('zi_sys').value, document.getElementById('zi_pla').value);
	var anz   = 0;
	var enf   = 0;
	var lag   = 0;
	var spd   = 110000000000;
	var time  = 0;

	if (start[0] != ziel[0]) {
		enf = 20000 * Math.abs(start[0] - ziel[0]);
	} else {
		if (start[1] != ziel[1]) {
			enf = 95 * Math.abs(start[1] - ziel[1]) + 2700;
		} else {
			if (start[2] != ziel[2]) {
				enf = 5 * Math.abs(start[2] - ziel[2]) + 1000;
			} else {
				enf = 5;
			}
		}
	}

	//Berechnung - Anzahl/Lagerkapazit?t
	for (i = 201; i < 215; i++) {
		if (i != 210) {
			if (document.getElementById('i' + i).value > 0) spd = Math.min(spd,retint(document.getElementById('s' + i).firstChild.nodeValue));
			anz = anz - -1 * document.getElementById('i' + i).value;
			lag = lag - -1 * retint(document.getElementById('l' + i).firstChild.nodeValue);
		}
	}

	var time1 = Math.round(time = (10 + (350 / document.getElementById('sel2').value * Math.sqrt(enf*1000/spd))));
	time = Math.round(time / speeduni);

//selon le depart
	var cheak;
	var chaine;
	var reg=new RegExp("^[0-9]{1,2}/[0-9]{1,2}/{1}[0-9]{4} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$","g");
	var reg2 = new RegExp("[ /:]+", "g");
	chaine = document.getElementById('heure_depart1').value;
	cheak = reg.test(chaine);
	if(cheak == true){
		var time2 = chaine.split(reg2);
		if( ((time2[0] > 0 && time2[0] < 32)&&(time2[1] > 0 && time2[1] < 13)&&(time2[3] >= 0 && time2[3] < 24)&&((time2[4] >= 0 && time2[4] < 60))&&((time2[5] >= 0 && time2[5] < 60))) == true ){
			if(anz == 0){
				document.getElementById('heure_arrive1').firstChild.nodeValue = '-';		
				document.getElementById('heure_retour1').firstChild.nodeValue = '-';
			}else{
				var Tdepart = new Date(time2[2], time2[1]-1 , time2[0], time2[3], time2[4], time2[5]);
				var depart = Tdepart.getTime();
				var arrive = (time * 1000) + depart;
				var retour = ( time * 2 * 1000 ) + depart ;
				document.getElementById('heure_arrive1').firstChild.nodeValue = newdate(arrive);
				document.getElementById('heure_retour1').firstChild.nodeValue = newdate(retour);
			}
		}else{
			document.getElementById('heure_arrive1').firstChild.nodeValue = '-';		
			document.getElementById('heure_retour1').firstChild.nodeValue = '-';
		}
	}else{
		document.getElementById('heure_arrive1').firstChild.nodeValue = '-';		
		document.getElementById('heure_retour1').firstChild.nodeValue = '-';
	}

	chaine = document.getElementById('heure_arrive2').value;
	var reg3=new RegExp("^[0-9]{1,2}/[0-9]{1,2}/{1}[0-9]{4} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$","g");
	cheak = reg3.test(chaine);
	if(cheak == true){
		var time3 = chaine.split(reg2);
		if( ((time3[0] > 0 && time3[0] < 32)&&(time3[1] > 0 && time3[1] < 13)&&(time3[3] >= 0 && time3[3] < 24)&&((time3[4] >= 0 && time3[4] < 60))&&((time3[5] >= 0 && time3[5] < 60))) == true ){
			if(anz == 0){
				document.getElementById('heure_depart2').firstChild.nodeValue = '-';		
				document.getElementById('heure_retour2').firstChild.nodeValue = '-';
			}else{
				var Tarrive = new Date(time3[2], time3[1]-1 , time3[0], time3[3], time3[4], time3[5]);
				var arrive = Tarrive.getTime();
				var depart = arrive - (time * 1000);
				var retour = (time * 1000)  + arrive ;
				document.getElementById('heure_depart2').firstChild.nodeValue = newdate(depart);		
				document.getElementById('heure_retour2').firstChild.nodeValue = newdate(retour);
			}
		}else{
			document.getElementById('heure_depart2').firstChild.nodeValue = '-';		
			document.getElementById('heure_retour2').firstChild.nodeValue = '-';		
		}
	}else{
		document.getElementById('heure_depart2').firstChild.nodeValue = '-';		
		document.getElementById('heure_retour2').firstChild.nodeValue = '-';
	}

	chaine = document.getElementById('heure_retour3').value;
	var reg4=new RegExp("^[0-9]{1,2}/[0-9]{1,2}/{1}[0-9]{4} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$","g");
	cheak = reg4.test(chaine);
	if(cheak == true){
		time4 = chaine.split(reg2);
		if( ((time4[0] > 0 && time4[0] < 32)&&(time4[1] > 0 && time4[1] < 13)&&(time4[3] >= 0 && time4[3] < 24)&&((time4[4] >= 0 && time4[4] < 60))&&((time4[5] >= 0 && time4[5] < 60))) == true ){
			if(anz == 0){
				document.getElementById('heure_depart3').firstChild.nodeValue = '-';		
				document.getElementById('heure_arrive3').firstChild.nodeValue = '-';
			}else{
				var Tretour = new Date(time4[2], time4[1]-1 , time4[0], time4[3], time4[4], time4[5]);
				var retour = Tretour.getTime();
				var depart = retour - ( time * 2 *1000 );
				var arrive = retour - ( time * 1000 );

				document.getElementById('heure_depart3').firstChild.nodeValue = newdate(depart);
				document.getElementById('heure_arrive3').firstChild.nodeValue = newdate(arrive);
			}
		}else{
			document.getElementById('heure_depart3').firstChild.nodeValue = '-';		
			document.getElementById('heure_arrive3').firstChild.nodeValue = '-';
		}
	}else{
		document.getElementById('heure_depart3').firstChild.nodeValue = '-';		
		document.getElementById('heure_arrive3').firstChild.nodeValue = '-';
	}

	//Berechnung - Treibstoff
	var verbrauch = 0;
	var gesverbrauch = 0;
	var shipspd = 0;
	var spd2 = 0;
	var num = 0;
	for (i = 0; i < data2.length; i++) {
		num = document.getElementById('i'+data2[i][0]).value;
		if (num != 0) {
			shipspd = retint(document.getElementById('s'+data2[i][0]).firstChild.nodeValue);
			spd2 = 35000 / ( time1 - 10 ) * Math.sqrt( enf * 10 / shipspd );
			verbrauch = num * (data2[i][4] + (data2[i][0] == 201 && document.getElementById('imp').value > 4 ? 10 : 0));
			gesverbrauch += verbrauch * enf / 35000 * Math.pow(spd2 / 10 + 1 , 2);
		}
	}


	document.getElementById('verbrauch').innerHTML = (anz == 0) ? '-' : pc(Math.round(gesverbrauch) + 1);

	document.getElementById('iges').firstChild.nodeValue = pc(anz);
	document.getElementById('lges').innerHTML = soute(lag,Math.round(gesverbrauch));
	document.getElementById('sges').firstChild.nodeValue = (spd == 110000000000) ? '-' : pc(spd);
	document.getElementById('distance').firstChild.nodeValue = pc(enf);
	document.getElementById('dauer').firstChild.nodeValue = (anz == 0) ? '-' : duration(time);
}

function duration(timex) {
	var ts = Math.round(mod(timex,60));
	if (ts < 10) ts = '0' + ts;
	timex = div(timex,60);
	var tm = mod(timex,60);
	if (tm < 10) tm = '0' + tm;
	var th = div(timex,60);
	if (th < 10) th = '0' + th;
	return th + ':' + tm + ':' + ts
}

function newdate(timestamp){
	var ladate = new Date();
	ladate.setTime(timestamp);
	var retour = ladate.getDate()+'/'+(ladate.getMonth()+1)+"/"+ladate.getFullYear()+" "+ladate.getHours()+":"+ladate.getMinutes()+":"+ladate.getSeconds();
	return retour;
}	

function clearflote(){
	for (i = 201; i < 215; i++) {
		if (i != 210) {
			document.getElementById('i' + i).value=0;
			document.getElementById('l' + i).value=0;
		}
	}
 	berechne_table('-1')
}

function chgpoint(lieu){
	var info = document.getElementById(lieu).value;
	if(info != 'choix'){
		var coordonne = document.getElementById(lieu).value.split(':');
		var prefix = "";
		if(lieu == "point_depart"){
			prefix = "st_";
		}
		if(lieu == "point_arrivee"){
			prefix = "zi_";
		}
		document.getElementById(prefix + 'gal').value=coordonne[0];
		document.getElementById(prefix + 'sys').value=coordonne[1];
		document.getElementById(prefix + 'pla').value=coordonne[2];
	}
}

function soute(cargaison,conso){
	var reste = (cargaison - conso);
	if (reste >= 0){
		reste = pc(reste);
		reste = ' (<font color="lime">'+reste+'</font>)';
	}else{
		reste = reste + (2 * -(reste));
		reste = pc(reste);
		reste = ' (<font color="red">-'+reste+'</font>)';
	}
	cargaison = pc (cargaison);
	return cargaison+reste;
}
</script>

<div class="hr">Calculateur de temps de vol</div>
<center><table border="0" cellspacing="0" cellpadding="0" >
	<tr>
		<td style="width:355px;">
			<table border="0" cellspacing="1" cellpadding="1" style="text-align:center;">
				<tr>
					<th>D&eacute;part</th>

					<th style="width:225px;"><input id="st_gal" type="text" maxlength="3" value="1" style="width:40px;" onBlur="chkval1(this);" onKeyUp="chkint(this);berechne();"> <input id="st_sys" type="text" maxlength="4" value="1" style="width:40px;" onBlur="chkval1(this);" onKeyUp="chkint(this);berechne();"> <input id="st_pla" type="text" maxlength="3" value="1" style="width:40px;" onBlur="chkval1(this);" onKeyUp="chkint(this);berechne();"></th>
				</tr>
				<tr>
					<th>Objectif</th>
					<th><input id="zi_gal" type="text" maxlength="2" value="1" style="width:40px;" onBlur="chkval1(this);" onKeyUp="chkint(this);berechne();"> <input id="zi_sys" type="text" maxlength="4" value="1" style="width:40px;" onBlur="chkval1(this);" onKeyUp="chkint(this);berechne();"> <input id="zi_pla" type="text" maxlength="3" value="1" style="width:40px;" onBlur="chkval1(this);" onKeyUp="chkint(this);berechne();">
						<select id="sel" onChange="berechne()">

							<option value="planet">Plan&egrave;te</option>
							<option value="tf">D&eacute;bris</option>
							<option value="mond">Lune</option>
						</select>
					</th>
				</tr>

				<tr>
					<th>Vitesse</th>
					<th>
						<select id="sel2" onChange="berechne()">
							<option value="1">100%</option>
							<option value=".9">90%</option>
							<option value=".8">80%</option>

							<option value=".7">70%</option>
							<option value=".6">60%</option>
							<option value=".5">50%</option>
							<option value=".4">40%</option>
							<option value=".3">30%</option>
							<option value=".2">20%</option>

							<option value=".1">10%</option>
						</select>
					</th>
				</tr>
				<tr>
					<th>Distance</th>
					<th><span id="distance">5</span></th>

				</tr>
				<tr>
					<th>Dur&eacute;e (un trajet)</th>
					<th><span id="dauer">-</span></th>
				</tr>
				<tr>
					<th>Consommation de carburant</th>

					<th><span id="verbrauch">-</span></th>
				</tr>
			</table>
		</td>
		<td style="text-align:center;vertical-align:top;">
			<table border="0" cellspacing="1" cellpadding="1" style="text-align:center;" >
				<tr>
					<th>Point de d&eacute;part</th>
					<th>
						<select id="point_depart" onChange="chgpoint('point_depart');berechne()">
<?php echo $option?>;
						</select>
					</th>
				</tr>
				<tr>
					<th>Point d'arrivée</th>
					<th>
						<select id="point_arrivee" onChange="chgpoint('point_arrivee');berechne()">
<?php echo $option?>;
						</select>				
				</tr>
				<tr>
		 			<td>&nbsp;</td><td></td>
				</tr>
				<tr>
					<th style="width:150px;">R&eacute;acteur &agrave; combustion</th>

					<th style="width:125px;"><input id="vbt" maxlength="2" type="text" value="<?PHP echo $pub_RC?>" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('-1')"></th>
				</tr>
				<tr>
					<th>R&eacute;acteur &agrave; impulsion</th>
					<th><input id="imp" maxlength="2" type="text" value="<?PHP echo $pub_RI?>" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('-1')"></th>
				</tr>
				<tr>

					<th>Propulsion Hyperespace</th>
					<th><input id="ha" maxlength="2" type="text" value="<?PHP echo $pub_PH?>" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('-1')"></th>
				</tr>
			</table>
		</td>
	</tr>
</table></center>
<br>
<table border="0" cellspacing="1" cellpadding="1" style="text-align:center;" >
	<tr>
		<th style="width:150px;">Heure de d&eacute;part</th>
		<th style="width:125px;"><input id="heure_depart1" maxlength="19" type="text" value="<?PHP echo date('d/m/Y H:i:j');?>" style="width:125px;" onBlur="chkval(this);" onKeyUp="berechne_table('-1')"></th>
		<th><span id="heure_depart2">-</span></th>
		<th><span id="heure_depart3">-</span></th>
	</tr>
	<tr>
		<th>Heure d&rsquo;arriv&eacute;e</th>
		<th><span id="heure_arrive1">-</span></th>
		<th style="width:125px;"><input id="heure_arrive2" maxlength="19" type="text" value="<?PHP echo date('d/m/Y H:i:j');?>" style="width:125px;" onBlur="chkval(this);" onKeyUp="berechne_table('-1')"></th>
		<th><span id="heure_arrive3">-</span></th>
	</tr>
	<tr>
		<th> Heure de retour</th>
		<th><span id="heure_retour1">-</span></th>
		<th><span id="heure_retour2">-</span></th>
		<th style="width:125px;"><input id="heure_retour3" maxlength="19" type="text" value="<?PHP echo date('d/m/Y H:i:j');?>" style="width:125px;" onBlur="chkval(this);" onKeyUp="berechne_table('-1')"></th>
	</tr>
</table>


<table border="0" cellspacing="1" cellpadding="1" >
	<tr class="light">

		<th style="width:115px;">Vaisseaux</th>
		<th>Nombre</th>
		<th style="width:115px;">Capacit&eacute; de chargement</th>
		<th style="width:115px;">Vitesse</th>
	</tr>
	<tr>

		<th>Petit transporteur</th>
		<th><input class="n" id="i201" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('201')"></th>
		<th><span id="l201">0</span></th>
		<th><span id="s201">5.000</span></th>
	</tr>
	<tr>
		<th>Grand transporteur</th>

		<th><input class="n" id="i202" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('202')"></th>
		<th><span id="l202">0</span></th>
		<th><span id="s202">7.500</span></th>
	</tr>
	<tr>
		<th>Chasseur l&eacute;ger</th>
		<th><input class="n" id="i203" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('203')"></th>

		<th><span id="l203">0</span></th>
		<th><span id="s203">12.500</span></th>
	</tr>
	<tr>
		<th>Chasseur lourd</th>
		<th><input class="n" id="i204" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('204')"></th>
		<th><span id="l204">0</span></th>

		<th><span id="s204">10.000</span></th>
	</tr>
	<tr>
		<th>Croiseur</th>
		<th><input class="n" id="i205" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('205')"></th>
		<th><span id="l205">0</span></th>
		<th><span id="s205">15.000</span></th>

	</tr>
	<tr>
		<th>Vaisseau de bataille</th>
		<th><input class="n" id="i206" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('206')"></th>
		<th><span id="l206">0</span></th>
		<th><span id="s206">10.000</span></th>
	</tr>

	<tr>
		<th>Vaisseau de colonisation</th>
		<th><input class="n" id="i207" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('207')"></th>
		<th><span id="l207">0</span></th>
		<th><span id="s207">2.500</span></th>
	</tr>
	<tr>

		<th>Recycleur</th>
		<th><input class="n" id="i208" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('208')"></th>
		<th><span id="l208">0</span></th>
		<th><span id="s208">2.000</span></th>
	</tr>
	<tr>
		<th>Sonde espionnage</th>

		<th><input class="n" id="i209" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('209')"></th>
		<th><span id="l209">0</span></th>
		<th><span id="s209">100.000.000</span></th>
	</tr>
	<tr>
		<th>Bombardier</th>
		<th><input class="n" id="i211" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('211')"></th>

		<th><span id="l211">0</span></th>
		<th><span id="s211">4.000</span></th>
	</tr>
	<tr>
		<th>Destructeur</th>
		<th><input class="n" id="i212" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('212')"></th>
		<th><span id="l212">0</span></th>

		<th><span id="s212">5.000</span></th>
	</tr>
	<tr>
		<th>Etoile de la mort</th>
		<th><input class="n" id="i213" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('213')"></th>
		<th><span id="l213">0</span></th>
		<th><span id="s213">100</span></th>

	</tr>
	<tr>
		<th>Traqueur</th>
		<th><input class="n" id="i214" maxlength="6" type="text" value="0" style="width:75px;" onBlur="chkval(this);" onKeyUp="chkint(this);berechne_table('214')"></th>
		<th><span id="l214">0</span></th>
		<th><span id="s214">100</span></th>

	</tr>
	<tr class="space"><th colspan="4"></th></tr>

	<tr>
		<th>Total</th>
		<th><span id="iges">0</span></th>
		<th><span id="lges">0</span</th>

		<th><span id="sges">-</span></th>
	</tr>
	<tr>
		<th colspan="4"><INPUT TYPE=BUTTON VALUE="Effacer tout" OnClick="javascript:clearflote()"></th>
	</tr>
</table>
<script type="text/javascript">
 	berechne_table('-1')
</script> 

<br />

<?php
if (file_exists('mod/tempsvols/version.txt')) { 
	$file = file('mod/tempsvols/version.txt'); 
}
?>
Temps de vol <?php echo trim($file[1]) ?><br>
Créé par Santory d'apres un script de marshen (2005-2006).<br>
Mise à jour par Shad (2011).</div>
<?php
require_once("views/page_tail.php");
?>