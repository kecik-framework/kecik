<?php
if ($id != '') {
    $sql = "SELECT * FROM data WHERE id=$id";
    $res = mysqli_query($this->dbcon, $sql);

    while ($data = mysqli_fetch_object($res)) {
        $name = $data->name;
        $email = $data->email;
    }
}
?>
<br/>
<form method="POST" action="<?php echo $url ?>">
    <div class="form-group">
        <label for="nama">Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name"
               value="<?php echo (isset($name)) ? $name : ''; ?>"/>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
               value="<?php echo (isset($email)) ? $email : ''; ?>"/>
    </div>

    <button type="submit" class="btn btn-default">Save</button>
</form>