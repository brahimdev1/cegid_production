<?php include 'connexion.php';
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas authentifié
    header('Location: index.php');
    exit();
}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>crud dashboard</title>
        <link rel="icon" href="medidis.png" />

	    <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
	    <!----css3---->
        <link rel="stylesheet" href="css/custom.css">
		
		
		<!--google fonts -->
	
	    <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
	
	
	<!--google material icon-->
      <link href="https://fonts.googleapis.com/css2?family=Material+Icons"rel="stylesheet">

  </head>
  <body>


<div class="wrapper">


        <div class="body-overlay"></div>
		
		<!-------------------------sidebar------------>
		     <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><img src="medidis.png" style="width: 180px; height: 40px;"/></h3>
            </div>
            <ul class="list-unstyled components">
			<li  class="active">
                    <a href="get,post.php" class="dashboard"><i class="material-icons" id="dashboard-link">dashboard</i>
					<span>Lancement & Réception</span></a>
                </li>
		
                

                <li  class="">
                    <a href="Grid.php"><i class="material-icons">aspect_ratio</i><span>Production
					</span></a>
                </li>
               
          
               <!-- Votre code de tableau de bord Bootstrap -->
    
       
				
				  
				<li class="dropdown">
                    <a href="#pageSubmenu5" data-toggle="collapse" aria-expanded="false" 
					class="dropdown-toggle">
					<i class="material-icons">equalizer</i><span>Suivi des Retards</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu5">
                        <li>
                            <a href="Matelas.php">Matelas</a>
                        </li>
                        <li>
                            <a href="Tapisserie.php">Tapisserie</a>
                        </li>
                        <li>
                            <a href="Molure-Cadre.php">Molures / Cadres</a>
                        </li>
                        <li>
                            <a href="#">Meuble massif / Kit</a>
                        </li>
                        <li>
                            <a href="#">Injection plastique </a>
                        </li>
                    
                        <li>
                            <a href="#">Luminaire </a>
                        </li>
                    </ul>
                </li>
               
                <li class="">
    <a href="Tiers.php"><i class="material-icons">library_books</i>
        <span>nom des Magasins</span>
    </a>
</li>
			   
			   
			 
               
			   
			     <li class="dropdown">
                    <a href="#pageSubmenu7" data-toggle="collapse" aria-expanded="false" 
					class="dropdown-toggle">
					<i class="material-icons">content_copy</i><span>etiquette Matelas</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu7">
                        <li>
                            <a href="dania.php">DANIA</a>
                        </li>
                        <li>
                            <a href="mediconfort.php">MEDICONFORT</a>
                        </li>
                        <li>
                            <a href="cashmere.php">CASHMERE</a>
                        </li>
                        <li>
                            <a href="medidorsal.php">DORSAL</a>
                        </li>
                        <li>
                            <a href="argan.php">ARGAN</a>
                        </li>
                        <li>
                            <a href="bio.php">BIO</a>
                        </li>
                        <li>
                            <a href="luxus.php">LUXUS</a>
                        </li>
                    </ul>
                </li>
               
                <li class="dropdown">
                    <a href="#pageSubmenu6" data-toggle="collapse" aria-expanded="false" 
					class="dropdown-toggle">
					<i class="material-icons">grid_on</i><span>Gestion</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu6">
                        <li>
                            <a href="Monitoring.php">Suivie de Production Magasin</a>
                        </li>
                        <li>
                            <a href="Monitoring-Marche.php">Suivie de Production Marche</a>
                        </li>
                     
                    </ul>
                </li>

				 <li  class="">
                    <a href="#"><i class="material-icons">library_books</i><span>Calender
					</span></a>
                </li>
             
               
               
            </ul>

           
        </nav>
		
		
		
		
		<!--------page-content---------------->
		
		<div id="content">
		   
		   <!--top--navbar----design--------->
		   
		   <div class="top-navbar">
		      <div class="xp-topbar">

                <!-- Start XP Row -->
                <div class="row"> 
                    <!-- Start XP Col -->
                    <div class="col-2 col-md-1 col-lg-1 order-2 order-md-1 align-self-center">
                        <div class="xp-menubar">
                               <span class="material-icons text-white">signal_cellular_alt
							   </span>
                         </div>
                    </div> 
                    <!-- End XP Col -->

                    <!-- Start XP Col -->
                    <div class="col-md-5 col-lg-3 order-3 order-md-2">
                        <div class="xp-searchbar">
                            <form>
                                <div class="input-group">
                                  <input type="search" class="form-control" 
								  placeholder="Search">
                                  <div class="input-group-append">
                                    <button class="btn" type="submit" 
									id="button-addon2">GO</button>
                                  </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End XP Col -->

                    <!-- Start XP Col -->
                    <div class="col-10 col-md-6 col-lg-8 order-1 order-md-3">
                        <div class="xp-profilebar text-right">
							 <nav class="navbar p-0">
                        <ul class="nav navbar-nav flex-row ml-auto">   
                            <li class="dropdown nav-item active">
                                <a href="#" class="nav-link" data-toggle="dropdown">
                                   <span class="material-icons">notifications</span>
								   <span class="notification">4</span>
                               </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#">You have 5 new messages</a>
                                    </li>
                                    <li>
                                        <a href="#">You're now friend with Mike</a>
                                    </li>
                                    <li>
                                        <a href="#">Wish Mary on her birthday!</a>
                                    </li>
                                    <li>
                                        <a href="#">5 warnings in Server Console</a>
                                    </li>
                                  
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
								<span class="material-icons">question_answer</span>

								</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" data-toggle="dropdown">
								<img src="" style="width:40px; border-radius:50%;"/>
                              
								<span class="xp-user-live"></span>
								</a>
								<ul class="dropdown-menu small-menu">
                                    <li>
                                        <a href="#">
										  <span class="material-icons">
person_outline
</span>Profile

										</a>
                                    </li>
                                    <li>
                                        <a href="#"><span class="material-icons">
settings
</span>Settings</a>
                                    </li>
                                    <li>
                                        <a href="logout.php"><span class="material-icons">
logout</span>Logout</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    
               
            </nav>
							
                        </div>
                    </div>
                    <!-- End XP Col -->

                </div> 
                <!-- End XP Row -->

            </div>
		     <div class="xp-breadcrumbbar text-center">
                <h4 class="page-title">Cegid Production</h4>  
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Booster</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cegid Production</li>
                  </ol>                
            </div>
			
		   </div>
		   

     




      



<!----------html code compleate----------->








  
     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="js/jquery-3.3.1.slim.min.js"></script>
   <script src="js/popper.min.js"></script>
   <script src="js/bootstrap.min.js"></script>
   <script src="js/jquery-3.3.1.min.js"></script>
  
  
  <script type="text/javascript">
        
		$(document).ready(function(){
		  $(".xp-menubar").on('click',function(){
		    $('#sidebar').toggleClass('active');
			$('#content').toggleClass('active');
		  });
		  
		   $(".xp-menubar,.body-overlay").on('click',function(){
		     $('#sidebar,.body-overlay').toggleClass('show-nav');
		   });
		  
		});
		
</script>

  



  </body>
  
  </html>


