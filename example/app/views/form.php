<?php
if ($id != '') {
    $sql = "SELECT * FROM data WHERE id=$id";
    $res = mysqli_query($dbcon, $sql);

    while ($data = mysqli_fetch_object($res)) {
        $nama = $data->nama;
        $email = $data->email;
    }
}
?>
<br/>
<form method="POST" action="<?php echo $url ?>">
    <div class="form-group">
        <label for="nama">Nama</label>
        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan Nama"
               value="<?php echo (isset($nama)) ? $nama : ''; ?>"/>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Masukan Email"
               value="<?php echo (isset($email)) ? $email : ''; ?>"/>
    </div>

    <button type="submit" class="btn btn-default">Simpan</button>
</form>