<html>
    <body>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
         
        Select the file to upload:
        <input type="file" name="myFile">
        <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
        <input type="submit" value="upload">
        </form>
    </body>
</html>