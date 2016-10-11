<[
use Kecik\Url;
]>
<br/>
<a href="<[ Url::to('tambah') ]>" class="btn btn-success">Tambah Data</a><br />

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
    <[
    $sql = "SELECT * FROM data";
    $res = mysqli_query($this->dbcon, $sql);
    $no = 1;

    while ($data = mysqli_fetch_object($res)):
    ]>
        <tr>
            <td><[= $no; ]></td>
            <td><[= $data->nama; ]></td>
            <td><[= $data->email; ]></td>
            <td>
                <a href="<[ Url::to('edit/' . $data->id) ]>" class="btn btn-primary">Ubah</a>
                <a href="<[ Url::to('delete/' . $data->id) ]>" class="btn btn-danger">Hapus</a>
            </td>
        </tr>
    <[
    $no++;
    endwhile;
    ]>

    </tbody>
</table>
