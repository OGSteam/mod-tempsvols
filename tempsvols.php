<?php /**
* tempsvols.php Page principal du mod
* @package [MOD] Temp de Vol
* @author Snipe <santory@websantory.net>
* @version 0.2d
*	created		: 07/01/2007
*   modified    : 31/12/2014 par Pitch314 (Mise en forme HTML avec les normes)
*/

 if (!defined('IN_SPYOGAME')) {
    die("Hacking attempt");
 }

 require_once("views/page_header.php");

 $query = "SELECT `active` FROM `".TABLE_MOD."` WHERE `action`='tempsvols' AND `active`='1' LIMIT 1";
 if (!$db->sql_numrows($db->sql_query($query))) {
    die("Hacking attempt");
 }
 
 //Recherche des technologies Combustion, Impulsion et Hyperespace du joueur :
	$query = "SELECT `RC`, `RI`, `PH` FROM ".TABLE_USER_TECHNOLOGY.
            " WHERE user_id='".$user_data['user_id']."'";
    $result = $db->sql_query($query);
    $fetch  = $db->sql_fetch_assoc($result);
	$pub_RC  = $fetch['RC'];
	$pub_RI  = $fetch['RI'];
	$pub_PH  = $fetch['PH'];

	$query = "SELECT `planet_name`, `coordinates` FROM ".TABLE_USER_BUILDING.
            " WHERE user_id='".$user_data['user_id']."'";
	$result = $db->sql_query($query);
	$option = "<option value='choix'>Choisir une Plan&egrave;te</option>";
	while($fetch = $db->sql_fetch_assoc($result)){
		$option .= "<option value='".$fetch['coordinates']."'>".
                   $fetch['planet_name']." [".$fetch['coordinates']."]</option>";
	}
?>
<script src="http://www.ogsteam.besaba.com/js/stat.js" type="text/javascript"> </script>

<div style="margin-bottom:10px; padding-top:1px; font-weight:bold; font-size:150%;">Calculateur de temps de vol</div>
<table style="border:0px; border-collapse:collapse; border-spacing:0px; padding:0px;">
 <tr>
  <td style="width:355px;">
   <table style="border:0px; border-collapse:separate; border-spacing:1px; padding:1px; text-align:center;">
    <tr>
     <th>D&eacute;part</th>
     <th style="width:225px;">
      <input id="st_gal" type="text" maxlength="3" value="1" style="width:40px;" onblur="checkVal1(this);" onkeyup="checkInt(this);berechne();" />
      <input id="st_sys" type="text" maxlength="4" value="1" style="width:40px;" onblur="checkVal1(this);" onkeyup="checkInt(this);berechne();" />
      <input id="st_pla" type="text" maxlength="3" value="1" style="width:40px;" onblur="checkVal1(this);" onkeyup="checkInt(this);berechne();" /></th>
    </tr>
    <tr>
     <th>Objectif</th>
     <th>
      <input id="ar_gal" type="text" maxlength="2" value="1" style="width:40px;" onblur="checkVal1(this);" onkeyup="checkInt(this);berechne();" />
      <input id="ar_sys" type="text" maxlength="4" value="1" style="width:40px;" onblur="checkVal1(this);" onkeyup="checkInt(this);berechne();" />
      <input id="ar_pla" type="text" maxlength="3" value="1" style="width:40px;" onblur="checkVal1(this);" onkeyup="checkInt(this);berechne();" />
      <select id="sel" onchange="berechne()">
       <option value="planet">Plan&egrave;te</option>
       <option value="tf">D&eacute;bris</option>
       <option value="mond">Lune</option>
      </select></th>
    </tr>
    <tr>
     <th>Vitesse</th>
     <th><select id="sel2" onchange="berechne()">
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
     </select></th>
    </tr>
    <tr>
     <th>Distance</th>
     <th><span id="distance">5</span></th>
    </tr>
    <tr>
     <th>Dur&eacute;e (un trajet)</th>
     <th><span id="duree">-</span></th>
    </tr>
    <tr>
     <th>Consommation de carburant</th>
     <th><span id="conso">-</span></th>
    </tr>
   </table>
  </td>
  <td style="vertical-align:top;">
   <table style="border:0px; border-collapse:separate; border-spacing:1px; padding:1px; text-align:center;">
    <tr>
     <th>Point de d&eacute;part</th>
     <th><select id="point_depart" onchange="chgpoint('point_depart');berechne()">
<?php echo $option?>
      </select></th>
    </tr>
    <tr>
     <th>Point d&rsquo;arriv&eacute;e</th>
     <th><select id="point_arrivee" onchange="chgpoint('point_arrivee');berechne()">
<?php echo $option?>
      </select></th>
    </tr>
    <tr><td>&nbsp;</td><td></td></tr>
    <tr>
     <th style="width:150px;">R&eacute;acteur &agrave; combustion</th>
     <th>
      <input id="combus" maxlength="2" type="text" value="<?PHP echo $pub_RC?>" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('-1')"></th>
    </tr>
    <tr>
     <th style="width:150px;">R&eacute;acteur &agrave; impulsion</th>
     <th>
      <input id="imp" maxlength="2" type="text" value="<?PHP echo $pub_RI?>" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('-1')"></th>
    </tr>
    <tr>
     <th style="width:150px;">Propulsion Hyperespace</th>
     <th>
      <input id="hyper" maxlength="2" type="text" value="<?PHP echo $pub_PH?>" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('-1')"></th>
    </tr>
   </table>
  </td>
 </tr>
</table>
<table style="border:0px; border-collapse:separate; border-spacing:1px; padding:1px; text-align:center;">
 <tbody><tr title="Uniquement dans les univers ayant des vitesses différentes entre la vitesse de production et la vitesse des vaisseaux.">
  <th></th>
   <th style="width:150px;">Vitesse univers vaisseaux</th>
   <th>
    <input id="speeduni" maxlength="4" value="<?php echo $server_config['speed_uni'];?>" style="width:75px;" onblur="checkVal1(this);" onkeyup="checkNum(this);berechne_table('-1')" type="text"></th>
  </tr>
  <tr>
  <th style="width:150px;"><span style="color:red; font-weight:bold; text-decoration:underline;" title="Pour version Ogame >5.8.5   En cours de projet dans la Gameforge
 distance galaxy entre 1 et 9 = 1G (si arrondi)
 distance system entre 1 et 499 = 1S (si arrondi)
 Formule : dist(a,b)=||a-b|-unitMax|  (ou unitMax=499(system), unitMax=9(galaxy)">[BETA !]</span> Univers arrondi</th>
  <th><input type='checkbox' id='galaxyR' name='galaxyR' onclick="berechne_table('-1')">Galaxies bouclées</input></th>
  <th><input type='checkbox' id='systemR' name='systemR' onclick="berechne_table('-1')">Systèmes bouclées</input></th>
  <th><input type='checkbox' id='universR' name='universR' onclick="javascript:verif_donnee();berechne_table('-1')">Univers entièrement bouclé</input></th>
 </tr></tbody></table>
<br />
<table style="border:0px; border-collapse:separate; border-spacing:1px; padding:1px; text-align:center;">
 <tr>
  <th style="width:150px;">Heure de d&eacute;part</th>
  <th style="width:125px;"><input id="heure_depart1" maxlength="19" type="text" value="<?PHP echo date('d/m/Y H:i:s');?>" onblur="checkVal(this);" onkeyup="berechne_table('-1')" /></th>
  <th style="width:125px;"><span id="heure_depart2">-</span></th>
  <th style="width:125px;"><span id="heure_depart3">-</span></th>
 </tr>
 <tr>
  <th>Heure d&rsquo;arriv&eacute;e</th>
   <th><span id="heure_arrive1">-</span></th>
   <th><input id="heure_arrive2" maxlength="19" type="text" value="<?PHP echo date('d/m/Y H:i:s');?>" onblur="checkVal(this);" onkeyup="berechne_table('-1')" /></th>
   <th><span id="heure_arrive3">-</span></th>
 </tr>
 <tr>
  <th> Heure de retour</th>
  <th><span id="heure_retour1">-</span></th>
  <th><span id="heure_retour2">-</span></th>
  <th><input id="heure_retour3" maxlength="19" type="text" value="<?PHP echo date('d/m/Y H:i:s');?>" onblur="checkVal(this);" onkeyup="berechne_table('-1')" /></th>
 </tr>
</table>
<br />
<table style="border:0px; border-collapse:separate; border-spacing:1px; padding:1px; text-align:center;">
 <tr class="light">
  <th style="width:115px;">Vaisseaux</th>
  <th style="width:115px;">Nombre</th>
  <th style="width:115px;">Capacit&eacute; de chargement</th>
  <th style="width:115px;">Vitesse</th>
 </tr>
 <tr>
  <th>Petit transporteur</th>
  <th><input class="n" id="i201" maxlength="6" type="text" value="0" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('201')" /></th>
  <th><span id="l201">0</span></th>
  <th><span id="s201">5.000</span></th>
 </tr>
 <tr>
  <th>Grand transporteur</th>
   <th><input class="n" id="i202" maxlength="6" type="text" value="0" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('202')" /></th>
   <th><span id="l202">0</span></th>
   <th><span id="s202">7.500</span></th>
 </tr>
 <tr>
  <th>Chasseur l&eacute;ger</th>
  <th><input class="n" id="i203" maxlength="6" type="text" value="0" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('203')" /></th>
  <th><span id="l203">0</span></th>
  <th><span id="s203">12.500</span></th>
 </tr>
 <tr>
  <th>Chasseur lourd</th>
  <th><input class="n" id="i204" maxlength="6" type="text" value="0" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('204')" /></th>
  <th><span id="l204">0</span></th>
  <th><span id="s204">10.000</span></th>
 </tr>
 <tr>
  <th>Croiseur</th>
  <th><input class="n" id="i205" maxlength="6" type="text" value="0" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('205')" /></th>
  <th><span id="l205">0</span></th>
  <th><span id="s205">15.000</span></th>
 </tr>
 <tr>
  <th>Vaisseau de bataille</th>
  <th><input class="n" id="i206" maxlength="6" type="text" value="0" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('206')" /></th>
  <th><span id="l206">0</span></th>
  <th><span id="s206">10.000</span></th>
 </tr>
 <tr>
  <th>Vaisseau de colonisation</th>
  <th><input class="n" id="i207" maxlength="6" type="text" value="0" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('207')" /></th>
  <th><span id="l207">0</span></th>
  <th><span id="s207">2.500</span></th>
 </tr>
 <tr>
  <th>Recycleur</th>
  <th><input class="n" id="i208" maxlength="6" type="text" value="0" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('208')" /></th>
  <th><span id="l208">0</span></th>
  <th><span id="s208">2.000</span></th>
 </tr>
 <tr>
  <th>Sonde espionnage</th>
  <th><input class="n" id="i209" maxlength="6" type="text" value="0" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('209')" /></th>
  <th><span id="l209">0</span></th>
  <th><span id="s209">100.000.000</span></th>
 </tr>
 <tr>
  <th>Bombardier</th>
  <th><input class="n" id="i211" maxlength="6" type="text" value="0" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('211')" /></th>
  <th><span id="l211">0</span></th>
  <th><span id="s211">4.000</span></th>
 </tr>
 <tr>
  <th>Destructeur</th>
  <th><input class="n" id="i212" maxlength="6" type="text" value="0" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('212')" /></th>
  <th><span id="l212">0</span></th>
  <th><span id="s212">5.000</span></th>
 </tr>
 <tr>
  <th>&Eacute;toile de la mort</th>
  <th><input class="n" id="i213" maxlength="6" type="text" value="0" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('213')" /></th>
  <th><span id="l213">0</span></th>
  <th><span id="s213">100</span></th>
 </tr>
 <tr>
  <th>Traqueur</th>
  <th><input class="n" id="i214" maxlength="6" type="text" value="0" style="width:75px;" onblur="checkVal(this);" onkeyup="checkInt(this);berechne_table('214')" /></th>
  <th><span id="l214">0</span></th>
  <th><span id="s214">10.000</span></th>
 </tr>
 <tr class="space"><th colspan="4"></th></tr>
 <tr>
  <th>Total</th>
  <th><span id="iges">0</span></th>
  <th><span id="lges">0</span></th>
  <th><span id="sges">-</span></th>
 </tr>
 <tr>
  <th colspan="4"><input type=button value="Effacer tout" onclick="javascript:clearflote()" /></th>
 </tr>
</table>

<script type="text/javascript">
var data2 = Array(
	//type,techno,v_base   ,speed2,conso, fret)
	//    0  , 1, 2        , 3    , 4   , 5   )
	Array(201, 1, 5000     , 10000, 10  , 5000),    //PT  (Petit transporteur)
	Array(202, 1, 7500     , 0    , 50  , 25000),   //GT  (Grand transporteur)
	Array(203, 1, 12500    , 0    , 20  , 50),      //cl  (Chasseur léger)
	Array(204, 2, 10000    , 0    , 75  , 100),     //CL  (Chasseur lourd)
	Array(205, 2, 15000    , 0    , 300 , 800),     //Cr  (Croiseur)
	Array(206, 3, 10000    , 0    , 500 , 1500),    //VB  (Vaisseau de bataille)
	Array(207, 2, 2500     , 0    , 1000, 7500),    //VC  (Vaisseau de colonisation)
	Array(208, 1, 2000     , 4000 , 300 , 20000),   //REC (Recycleur)
	Array(209, 1, 100000000, 0    , 1   , 5),       //Sonde
	Array(211, 2, 4000     , 5000 , 1000, 500),     //Bom (Bombardier)
	Array(212, 3, 5000     , 0    , 1000, 2000),    //Des (Destructeur)
    Array(213, 3, 100      , 0    , 1   , 1000000), //RIP (Étoile de la mort) 
	Array(214, 3, 10000    , 0    , 250 , 750)      //TR  (Traqueur)
);

function verif_donnee() {
    document.getElementById('galaxyR').checked = document.getElementById('universR').checked;
    document.getElementById('systemR').checked = document.getElementById('universR').checked;
}

function div(a, b) {
	return Math.floor(a / b);
}

function mod(a, b) {
	return a - Math.floor(a / b) * b;
}

//Convertion nombre pour affichage avec séparateur des milliers
function pc(s) {
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

function retnum(s) {
	var sxx = '';
	s = s + '';
	var sx = s.toUpperCase();
    var decimal = false;
	for(ir = 0; ir < sx.length; ir++) {
        if(!decimal && (sx.charAt(ir)==',' || sx.charAt(ir)=='.')){
            decimal = true;
            sxx = sxx + '.';
        } else {
            if(sx.charCodeAt(ir) >= 48 && sx.charCodeAt(ir) <= 57) {
                sxx = sxx + sx.charAt(ir);
            }
        }
	}
	return sxx;
}

function checkNum(id) {
	if(id.value != retnum(id.value)) {
		id.value = retnum(id.value);
	}
}
function checkInt(id) {
	if(id.value != retint(id.value)) {
		id.value = retint(id.value);
	}
}

function checkVal(id) {
	if (id.value == '') id.value = '0';
}
function checkVal1(id) {
	if (id.value == '') id.value = '1';
}

function berechne_table(id) {
	//alert(id);
	id++;
	id--;
	var technos = Array(document.getElementById('combus').value,  document.getElementById('imp').value,  document.getElementById('hyper').value);
	var speed = 0;

//-- data2 array --//
	for (i = 0; i < data2.length; i++) {
		if ((data2[i][0] == id) || (id == -1)) {
			speed = data2[i][2] * (1 + technos[data2[i][1]-1] * data2[i][1] / 10); //normale
			if (data2[i][3] != 0) {//-exceptions-
				if ((data2[i][0] == 201) && (technos[1] > 4)) speed = data2[i][3] * (1 + technos[1] * 2 / 10); //pt imp
				if ((data2[i][0] == 211) && (technos[2] > 7)) speed = data2[i][3] * (1 + technos[2] * 3 / 10); //bomb hyp
				if (data2[i][0] == 208) {
					if (technos[1] > 16) speed = data2[i][3] * (1 + technos[1] * 2 / 10); //rec imp
					if (technos[2] > 14) speed = data2[i][3] * 1.5 * (1 + technos[2] * 3 / 10); //rec hyp
				}	
			}
			document.getElementById('s' + data2[i][0]).firstChild.nodeValue = pc(Math.round(speed));
			document.getElementById('l' + data2[i][0]).firstChild.nodeValue = pc(data2[i][5] * document.getElementById('i' + data2[i][0]).value);
		}
	}
	berechne()
}

//Fonction permettant la gestion de l'affichage des durée. (Réduction de code)
//s1 = heure donnée, s*=(début,arrivée,retour), a = temps pour s1->s2, b = temps pour s1->s3
function berechne_under(s1, s2, s3, nb, time, a, b) {
    var cheak;
	var chaine;
	var reg  = new RegExp("^[0-9]{1,2}/[0-9]{1,2}/{1}[0-9]{4} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$","g");
	var reg2 = new RegExp("[ /:]+", "g");
    
	chaine = document.getElementById(s1).value;
	cheak = reg.test(chaine);
    document.getElementById(s2).firstChild.nodeValue = '-';		
	document.getElementById(s3).firstChild.nodeValue = '-';
	if(cheak == true){
		var time2 = chaine.split(reg2);
		if( ((time2[0] > 0 && time2[0] < 32)&&(time2[1] > 0 && time2[1] < 13)&&(time2[3] >= 0 && time2[3] < 24)&&((time2[4] >= 0 && time2[4] < 60))&&((time2[5] >= 0 && time2[5] < 60))) == true ){
			if(nb != 0) {
				var Tdepart = new Date(time2[2], time2[1]-1 , time2[0], time2[3], time2[4], time2[5]);
				var depart = Tdepart.getTime();
				document.getElementById(s2).firstChild.nodeValue = newdate(depart + (time * 1000) * a);
				document.getElementById(s3).firstChild.nodeValue = newdate(depart + (time * 1000) * b);
			}
		}
	}
}

function calc_distance(a, b, type) {//a-b
    var max_type = 0;
    var typeArrondi = false;
    
    switch(type){
        case 0: //Galaxy
            max_type = <?php echo $server_config['num_of_galaxies']; ?>; //9
            typeArrondi = document.getElementById('galaxyR').checked;
            break;
        case 1: //System
            max_type = <?php echo $server_config['num_of_systems']; ?>; //499
            typeArrondi = document.getElementById('systemR').checked;
            break;
    }
    if(typeArrondi) {
        if(Math.abs(a - b) < max_type/2) {
            return Math.abs(a - b);//|a-b|
        } else {
            return Math.abs(Math.abs(a - b) - max_type); //||a-b| - base|
        }
    } else {
        return Math.abs(a - b);//|a-b|
    }
}
function berechne() {
	var start    = Array(document.getElementById('st_gal').value, document.getElementById('st_sys').value, document.getElementById('st_pla').value);
	var arrivee  = Array(document.getElementById('ar_gal').value, document.getElementById('ar_sys').value, document.getElementById('ar_pla').value);
	var nb  = 0;
	var dist = 0;
	var fret  = 0;
	var speed = 110000000000;
	var time = 0;

	if (start[0] != arrivee[0]) {
		dist = 20000 * calc_distance(start[0], arrivee[0], 0);
	} else {
		if (start[1] != arrivee[1]) {
			dist = 95 * calc_distance(start[1], arrivee[1], 1) + 2700;
		} else {
			if (start[2] != arrivee[2]) {
				dist = 5 * calc_distance(start[2], arrivee[2], 2) + 1000;
			} else {
				dist = 5;
			}
		}
	}

	for (i = 201; i < 215; i++) {
		if (i != 210) {
			if (document.getElementById('i' + i).value > 0) speed = Math.min(speed,retint(document.getElementById('s' + i).firstChild.nodeValue));
			nb = nb - -1 * document.getElementById('i' + i).value;
			fret = fret - -1 * retint(document.getElementById('l' + i).firstChild.nodeValue);
		}
	}

	var time1 = Math.round(time = (10 + (350 / document.getElementById('sel2').value * Math.sqrt(dist*1000/speed))));
    var speeduni = document.getElementById('speeduni').value;
    if(speeduni == 0) speeduni = 1;
    time = Math.round(time / speeduni);

//selon le depart
    berechne_under('heure_depart1', 'heure_arrive1', 'heure_retour1', nb, time, 1, 2);
    berechne_under('heure_arrive2', 'heure_depart2', 'heure_retour2', nb, time, -1, 1);
    berechne_under('heure_retour3', 'heure_depart3', 'heure_arrive3', nb, time, -2, -1);

	//Berechnung - Treibstoff
	var conso = 0;
	var gesconso = 0;
	var shipspeed = 0;
	var speed2 = 0;
	var num = 0;
	for (i = 0; i < data2.length; i++) {
		num = document.getElementById('i'+data2[i][0]).value;
		if (num != 0) {
			shipspeed = retint(document.getElementById('s'+data2[i][0]).firstChild.nodeValue);
			speed2 = 35000 / ( time1 - 10 ) * Math.sqrt( dist * 10 / shipspeed );
	      
			basisconso = data2[i][4];
      
			if (data2[i][0] == 201 && document.getElementById('imp').value > 4) basisconso = basisconso * 2;
			if (data2[i][0] == 208 && document.getElementById('hyper').value > 14) basisconso = basisconso * 3;
			else if (data2[i][0] == 208 && document.getElementById('imp').value > 16) basisconso = basisconso * 2;
      
			conso = num * basisconso;
			gesconso += conso * dist / 35000 * Math.pow(speed2 / 10 + 1 , 2);
		}
	}
    
	document.getElementById('conso').innerHTML = (nb == 0) ? '-' : pc(Math.round(gesconso) + 1);
	document.getElementById('iges').firstChild.nodeValue = pc(nb);
	document.getElementById('lges').innerHTML = soute(fret,Math.round(gesconso));
	document.getElementById('sges').firstChild.nodeValue = (speed == 110000000000) ? '-' : pc(speed);
	document.getElementById('distance').firstChild.nodeValue = pc(dist);
	document.getElementById('duree').firstChild.nodeValue = (nb == 0) ? '-' : duration(time);
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

function addZero(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

function newdate(timestamp){
	var d = new Date();
    
	d.setTime(timestamp);
	var retour = addZero(d.getDate())+'/'+addZero((d.getMonth()+1))+"/"+d.getFullYear()+" "+
                 addZero(d.getHours())+":"+addZero(d.getMinutes())+":"+addZero(d.getSeconds());
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
			prefix = "ar_";
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
        reste = ' (<span style="color:lime">'+reste+'</span>)';
	}else{
		reste = reste + (2 * -(reste));
		reste = pc(reste);
        reste = ' (<span style="color:red">'+reste+'</span>)';
	}
	cargaison = pc (cargaison);
	return cargaison+reste;
}
</script>
<script type="text/javascript">
 	berechne_table('-1')
</script>
<br />
<?php
 $filename = "mod/tempsvols/version.txt";
 if (file_exists($filename)){
    $file = file($filename);
    $mod_version = trim($file[1]);
 } 
 echo "<div style='text-align:center;font-size:0.8em'>Temps de Vol ".$mod_version."<br />";
 echo "Cr&eacute;&eacute; par <a>Santory</a> d&rsquo;apr&egrave;s un script de marshen (2005-2006).<br />";
 echo "Modifi&eacute; par <a>Shad</a> (2011), mise &agrave; jour par <a>Pitch314</a> (2015)<br />";
 //echo "<font size='1'><a href='http://ogsteam.fr/' target='_blank'>plus d'informations</a>.</font>";
 echo "</div>";
 require_once("views/page_tail.php");
?>