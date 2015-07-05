<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link href="../css/adminTemplate.css" rel="stylesheet" type="text/css">
</head>
<body>
<a id="logout" href="../php/logout.php" class="">Logout</a>
<div class="container">
	<header>
                <h1>Welcome Admin</h1></header>
<div id="wrapper">
  <div id="tabContainer">
    <div id="tabs">
      <ul>
        <li id="tabHeader_1">Partial Matches</li>
        <li id="tabHeader_2">Perfect Matches</li>
        <li id="tabHeader_3">New Contacts</li>
		<li id="tabHeader_4">Key Datapoints</li>
      </ul>
    </div>
    <div id="tabscontent">
      <div class="tabpage" id="tabpage_1">
        <h2>Partial Matches</h2>
        <p>Show the results here</p>
      </div>
      <div class="tabpage" id="tabpage_2">
        <h2>Perfect Matches</h2>
        <p>Show the results here</p>
      </div>
      <div class="tabpage" id="tabpage_3">
        <h2>New Contacts</h2>
        <p>Show the results here</p>
      </div>
	  <div class="tabpage" id="tabpage_4">
        <h2>Key Datapoints</h2>
        <p>Show the results here</p>
      </div>
    </div>
  </div>
 </div>
<script src="../js/adminScript.js"></script>
</body>
</html>