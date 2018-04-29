<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST)) {

        //Get svn username and password from request parameters
        //Get version number to move to
        $values = $_POST;
        $version = $values['revision'];
        $path = $values['path'];
        $username = $values['user'];
        $password = $values['pass'];
        //validate version number
        //Path where you have checked out your project
        // $rpath = '/var/www/'.$path;
        //Command to svn update
        if ($values['cmds'] != '')
            $cmd = $values['cmds'];
        else
            $cmd = "cd $path; svn cleanup; svn --non-interactive --username $username --password $password up";

        echo $cmd;

        $out = shell_exec($cmd);

        echo "<br/>output<br/>" . $out;
    }
}
?>
<form action="svnup.php" method="post">
    path: <input type="text" required name="path"><br>
    rev: <input type="text" name="revision"><br>
    user: <input type="text" required name="user"><br>
    pass: <input type="password" required name="pass"><br>
    cmd: <input type="text"  name="cmds"><br>
    <input type="submit">
</form>