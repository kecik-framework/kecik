<br/>
<a href="<?php $this->url->to('tambah') ?>" class="btn btn-success">Tambah Data</a><br/>

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>NO</th>
        <th>NAMA</th>
        <th>EMAIL</th>
        <th>AKSI</th>
    </tr>
    </thead>

    <tbody>
    <?php
    $sql = "SELECT * FROM data";
    $res = mysqli_query($this->dbcon, $sql);
    $no = 1;

    while ($data = mysqli_fetch_object($res)):
        ?>
        <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $data->nama; ?></td>
            <td><?php echo $data->email; ?></td>
            <td>
                <a href="<?php $this->url->to('edit/' . $data->id) ?>" class="btn btn-primary">Ubah</a>
                <a href="<?php $this->url->to('delete/' . $data->id) ?>" class="btn btn-danger">Hapus</a>
            </td>
        </tr>
        <?php
        $no++;
    endwhile;
    ?>

    </tbody>
</table>
