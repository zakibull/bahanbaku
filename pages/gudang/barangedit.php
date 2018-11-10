<?php
ob_start();
if(isset($_GET['id']))
{
	$rs=mysql_query("Select * from data_barang where sha1(kode_barang)='".$_GET['id']."'");
	$row=mysql_fetch_array($rs);
?>
<form name="form1" method="post" action="?cat=gudang&page=barangedit&id=<?php echo $_GET['id']; ?>&edit=1">
 
      <label>Nama Barang</label>
      <input type="text" name="namabarang" id="namabarang" value="<?php echo $row['nama_barang']; ?>">
      <label>Jenis Barang</label>
     <select name="jenis" id="jenis">
        <option value="Vans">Vans</option>
        <option value="Adidas">Adidas</option>
      </select>
   
      <p></p>
      <input type="submit" class="btn btn-primary" name="button" id="button" value="Ubah">&nbsp;&nbsp;<input type="reset" class="btn btn-danger" name="reset" id="reset" value="Batal" onclick="window.location='?cat=gudang&page=barang'">
</form>
<?php
ob_end_flush();
}else{
	echo "<script>window.location='?cat=gudang&page=barang'</script>";
}
?>
<?php
if(isset($_GET['edit']))
{
	
	$rs=mysql_query("Update data_barang SET nama_barang='".$_POST['namabarang']."',jenis_barang='".$_POST['jenis']."' Where sha1(kode_barang)='".$_GET['id']."'");
	if($rs)
	{
		echo "<script>window.location='?cat=gudang&page=barang'</script>";
	}
}
?>
