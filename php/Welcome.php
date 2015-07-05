<!DOCTYPE html>
    <head>
        <meta charset="UTF-8" />
        <title>Welcome</title> 
        <meta name="description" content="Mapping Tool Welcome Page" />
        <link rel="stylesheet" type="text/css" href="../css/welcome.css" />
    </head>
    <body>
	<a id="logout" href="../php/Logout.php" class="">Logout</a>
        <div class="container">
            <header>
                <h1>Welcome <?php session_start(); echo(strtoupper($_SESSION['username'])); ?></h1>
            </header>
            <section>				
                <div id="container_welcome">
                    
                        <h3>Please select from the below options to add contacts</h3>

                   <div id="content">
					   <a href="../html/ManualUploadTemplate.html" class="leftNav buttonAnchor">Manual Template</a>
					   <a href="../html/FileUploadTemplate.html" class="rightPara buttonAnchor">File Upload</a>
					</div> 
                </div>  
            </section>
        </div>
    </body>
</html>