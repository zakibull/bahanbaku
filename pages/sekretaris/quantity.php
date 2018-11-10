<script src="js/jquery-ui.js"></script>
<?php
if(isset($_GET['cal']))
{
	
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="53%"><h1>Kalkulator Q</h1></td>
    <td width="47%"><h1>Hasil</h1></td>
  </tr>
  <tr>
    <td><form method="post">
<?php
$kode_barang=$_GET['id'];
$tttt=$_SESSION['tgl'];
$q=mysql_query("Select count(id_keluar) as numrow,sum(jumlah) as jml,jumlah from barang_keluar Where kode_barang='".$kode_barang."' and month(tgl)='".($_SESSION['tgl']-1)."'");
$rw=mysql_fetch_array($q);
$rc=mysql_num_rows($q);
if($rc==0)
{
	echo "<script>alert('Data kosong pada bulan ini');window.location='?cat=sekretaris&page=quantity'</script>";
}
$rt2=($rw['jml']/$rw['numrow']);
$sd=array();
$q2=mysql_query("Select * from barang_keluar Where kode_barang='".$kode_barang."' and month(tgl)='".($_SESSION['tgl']-1)."'");
while($rww=mysql_fetch_array($q2))
{
$sd[]=$rww['jumlah'];
}
?>
<label>Jumlah Transaksi</label>
<input type="text" readonly="readonly" name="jt" value="<?php echo $rw['numrow']; ?>" />
<label>Jumlah QTY</label>
<input type="text" readonly="readonly" name="qty" value="<?php echo $rw['jml']; ?>" />
<label>Rata-Rata Keluar</label>
<input type="text" readonly="readonly" name="rata" value="<?php echo $rt2; ?>" />
<label>Standar Deviation</label>
<input type="text" name="v_sd" readonly="readonly" value="<?php echo round(standard_deviation($sd),2); ?>" />
<label>Periode Pemesanan</label>
<input type="text" name="pp"/>
<label>Reserve</label>
<input type="text" name="rsss"/>
<label>Lead Time</label>
<input type="text" name="lt"/>
<?php

$q1=mysql_query("Select * from data_persediaan where kode_barang='".$kode_barang."'");
$rw1=mysql_fetch_array($q1);

?>
<label>Stok Tersedia</label>
<input type="text" readonly="readonly" name="tersedia" value="<?php echo $rw1['stok_tersedia']; ?>" />
<label>Stok order</label>
<input type="text" readonly="readonly" name="order" value="<?php echo $rw1['keluar']; ?>" />
<p></p>
<input type="submit" name="button"  class="btn btn-primary" value="Hitung" />
</form></td>
    <td>

<?php
if(isset($_POST['button']))
{
echo "<form method='post' name='calform'>";
$v_rata2=round($_POST['rata'],2);
$v_periode=round($_POST['pp'],2);
$v_leadtime=round($_POST['lt'],2);
$v_tersedia=round($_POST['tersedia'],2);
$vv_sd=round($_POST['v_sd'],2);
$v_order=round(0,2);
$reserve=$_POST['rsss'];
$safety_stock=round($vv_sd*(1.34*sqrt($_POST['lt'])),2);
$r1=$v_periode+$v_leadtime+$reserve;
$r2=$v_tersedia+$v_order;
$r3=$v_rata2*$r1;
$QQ=round($r3 - $r2,2);
echo "<h3>Pemakaian Rata-rata : ".$v_rata2."<br>";
echo "Periode Pemesanan : ".$v_periode."<br>";
echo "Lead Time : ".$v_leadtime."<br>";
echo "Safety Stock : ".$safety_stock."<br>";
echo "Stok Tersedia : ".$v_tersedia."<br>";
echo "Stok Order : ".$v_order."<br>";
echo "EOI : ".$QQ."<br>";
echo "Standar Deviation ".$vv_sd."</h3>";
echo "
<input type='hidden' name='a' value='$v_rata2' />
<input type='hidden' name='b' value='$v_periode' />
<input type='hidden' name='c' value='$v_leadtime' />
<input type='hidden' name='d' value='$safety_stock' />
<input type='hidden' name='e' value='$v_tersedia' />
<input type='hidden' name='f' value='$v_order' />
<input type='hidden' name='g' value='$QQ' />";
echo "<p></p>";
?>
<?php
if($QQ > 0)
{
echo '<input type="submit" name="button3" class="btn btn-primary" value="Simpan" />';
}else{
echo "Tidak perlu diorder, stok tersedia masih banyak";
}
?>
<?php
echo "</form>";
}
?>
<?php

if(isset($_POST['button3']))
{
	$q=mysql_query("Insert into data_perencanaan (`tgl`, `kode_barang`, `rata`, `periode`, `lead`, `safety`, `tersedia`, `order`, `eoq`) values ('".date("Y-m-d")."','".$_GET['id']."','".$_POST['a']."','".$_POST['b']."','".$_POST['c']."','".$_POST['d']."','".$_POST['e']."','".$_POST['f']."','".$_POST['g']."')");
	if($q)
	{
		$q2=mysql_query("update data_persediaan SET stok_akhir=stok_tersedia-keluar,rata_keluar='".$_POST['a']."',safety_stok='".$_POST['d']."' Where kode_barang='".$_GET['id']."'") or die(mysql_error());
		if($q2)
		{
		echo "<script>alert('Sudah disimpan');window.location='?cat=sekretaris&page=quantity'</script>";
		}
	}
}
?>
</td>
  </tr>
</table>
<?php
}else{
?>
<form method="post" name="form2">
<label>Bulan</label>
<select name="bln" id="bln">
        <option value="01">Januari</option>
        <option value="02">Februari</option>
        <option value="03">Maret</option>
        <option value="04">April</option>
        <option value="05">Mei</option>
        <option value="06">Juni</option>
        <option value="07">Juli</option>
        <option value="08">Agustus</option>
        <option value="09">September</option>
        <option value="10">Oktober</option>
        <option value="11">November</option>
        <option value="12">Desember</option>
      </select>
<label>Pilih Barang</label>
<label for="kodebarang"></label>
      <input type="text" name="kodebarang" id="kodebarang" placeholder="Pilih Barang.."  onClick="window.open('http://localhost/bahanbaku/pages/web/viewbarang.php','popuppage','width=500,toolbar=0,resizable=0,scrollbars=no,height=400,top=100,left=100');">
      <label> Nama Barang</label>
      <input name="namabarang" type="text" id="namabarang" readonly="readonly">
      <p></p>
<input type="submit" name="button2" class="btn btn-primary" value="Lanjut" />
<?php
if(isset($_POST['button2']))
{
	$_SESSION['tgl']=$_POST['bln'];
	echo "<script>window.location='?cat=sekretaris&page=quantity&cal=1&id=".$_POST['kodebarang']."'</script>";
}
?>
</form>
<?php
}
?>
<?php
function standard_deviation($aValues)
{
    $fMean = array_sum($aValues) / count($aValues);
    //print_r($fMean);
    $fVariance = 0.0;
    foreach ($aValues as $i)
    {
        $fVariance += pow($i - $fMean, 2);

    }       
    $size = count($aValues) - 1;
    return (float) sqrt($fVariance)/sqrt($size);
}
?>