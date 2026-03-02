<?php 
session_start();
if(isset($_SESSION['user_data'])){
	if(!isset($_SESSION['user_data']['perms'][2])){
		header('Location: '.$_SESSION['user_data']['perms'][array_keys($_SESSION['user_data']['perms'])[0]]->url);
		exit();
	}
}
else{
	header('Location: ../../index.html');
	exit();
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Nacionales</title>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
		<meta http-equiv="cache-control" content="no-cache"/>
		<meta http-equiv="pragma" content="no-cache"/>

		<link rel="shortcut icon" href="../../assets/img/fge.png"/>
		<link rel="stylesheet" href="assets/css/main.css?v=<?php echo time(); ?>"/>
		<link rel="stylesheet" href="assets/css/styles.css?v=<?php echo time(); ?>"/>

		<link rel="stylesheet" href="../../css/styles.css?v=<?php echo time(); ?>">
		<link rel="stylesheet" href="../../css/dropdown-style.css?v=<?php echo time(); ?>">
		<link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">

		<script src="../../node_modules/jquery/dist/jquery.min.js" ></script>
		<script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js" ></script>
		<script src="../../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

		<script src="../../js/script.js?v=<?php echo time(); ?>"></script>
		<script src="js/script.js?v=<?php echo time(); ?>"></script>
		<script src="js/microdata.js?v=<?php echo time(); ?>"></script>
	</head>
	<body class="is-preload">

		<div class="loader-div"></div>

		<div id="wrapper">
			<div id="main">
				<header id="navbar">
					<div class="dropdown username-section">
						<div class="dropbtn">
							<img onclick="myFunction()" src="../../assets/img/user.png" alt="">

							<div onclick="myFunction()">
								<div id="username"><?php echo $_SESSION['user_data']['name'].' '.$_SESSION['user_data']['paternal_surname'].' '.$_SESSION['user_data']['maternal_surname']; ?></div>
								<div id="role"><?php echo $_SESSION['user_data']['type'] == 1 ? 'Administrador' : ''; ?></div>
							</div>
						</div>

						<div id="myDropdown" class="dropdown-content">
							<a onclick="closeSession()">Cerrar Sesión</a>
						</div>
					</div>
				</header>

				<div class="background-header">
					<h1>MICRODATO</h1>
				</div>

				<div class="inner">
					<section>
						<form class="search-form" action="#">	

							<div class="form-row">
								<div class="col-md-6 form-group">
									<label style="font-weight:bold">Opción: *</label>
				
									<select id="main-search-option" class="form-control" required="true">									
										<option value ="Homicidio" selected>Homicidio</option>
										<option value ="Feminicidio">Feminicidio</option>
										<option value ="Secuestro">Secuestro</option>
									</select>	
								</div>
				
								<div class="col-md-6 form-group">
									<label style="font-weight:bold">Mes y año: *</label>
									<input id="main-search-search-month" type="month" class="form-control" required="true">
								</div>
							</div>

							<div class="form-buttons">	
								<button type="button" class="btn btn-outline-success rounded-button" onclick="searchMicrodata()">Descargar EXCEL</button>
							</div>

						</form>
					</section>
				</div>
			</div>
			
			<div id="sidebar">
				<div class="inner">
					<nav id="menu">
						<header class="major">
							<h2><a id="text-logo">FGE</a>&nbsp;&nbsp;&nbsp;BASES NACIONALES</h2>
						</header>
						<ul id="menu-list"></ul>
					</nav>

					<footer id="footer">
						<p>Sistema de Bases Nacionales v1-26.02.26</p>
					</footer>
				</div>
			</div>
		</div>

		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/browser.min.js"></script>
		<script src="assets/js/breakpoints.min.js"></script>
		<script src="assets/js/util.js"></script>
		<script src="assets/js/main.js"></script>
	</body>
</html>