<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
<HEAD>
	<title>FV-iSCARdb</title>
	<META NAME="Generator" CONTENT="TextPad 4.6">
	<META NAME="Author" CONTENT="premkrishnan">
	<META NAME="Keywords" CONTENT="iscar, rapd">
	<META NAME="Description" CONTENT="Predicted SCAR markers for fruit and vegetables crops">
	<link rel="icon" href="images/icon.gif" />
	<!--// CSS //-->
	<link href="css/jquery.dataTables.min.css" rel="stylesheet">	
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<!--// JS //-->
	<script src="jquery/jquery-3.3.1.js"></script>
	<script src="jquery/jquery.min.js"></script>
	<script src="jquery/jquery.dataTables.min.js"></script>
	<script>
	$(document).ready(function() {
		$('#resultTable').DataTable( {
			"scrollX": true
		} );
	} );
	</script>

</HEAD>

<BODY>

    <!--========================================
           Header
    ========================================-->

    <!--//** Navigation**//-->
    <nav class="navbar navbar-default navbar-fixed white no-background bootsnav navbar-scrollspy" data-minus-value-desktop="70" data-minus-value-mobile="55" data-speed="1000">
        <div class="container">
            <!-- Start Header Navigation -->
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php"><img src="images/logo.png" alt="logo"></a>
            </div>
            <!-- End Header Navigation -->		
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-menu">
                <ul class="nav navbar-nav navbar-right">
                    <li class="active scroll"><a href="index.php">Home</a></li>
					<li class="active scroll"><a href="credits_page.php">Credits</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
    </nav>	
	
	<!-- content -->

	<div align="center">
	<div id="resulttablediv" style="width:90%;padding:15px;">
		<table id="resultTable" class="display nowrap" style="width:99%;border-collapse: collapse;font-family:sans-serif; font-size:12px;  " >
					<?php
						
						include('dbconfig.php');
						$NoResult = 0;
						
						if(isset($_POST['submit']))
						{
							$SP = $_POST['species'];
							$ST = $_POST['seqtype'];
						}

						if( ($SP!='Not known') && ($ST!='Not known') )
						{
							$FieldsId = 'all';
						}				
						if( ($SP!='Not known') && ($ST=='Not known') )
						{
							$FieldsId = 'sp';
						}
						if( ($SP=='Not known') && ($ST!='Not known') )
						{
							$FieldsId = 'st';
						}
								
						if($FieldsId == 'all')
						{
							//Open a new file (w mode) for writing results - heading writing
							$ResultFile = 'Result.txt';
							$RF = fopen($ResultFile,'w') or die ("Cannot open file!");
							$Heading = "Species Name\tSequence ID\tSequence Type\tPrimer Name\tPrimer Type\tProduct Size\tForwardSCAR\tReverseSCAR\n\n";
							fwrite($RF,$Heading);
							fclose($RF);
							//close the file

							// Search using species name and sequence type
							$query = "select a.SequenceID,a.ProductSize,a.ForwardSCAR,a.ReverseSCAR,c.PrimerName,c.PrimerType,d.SequenceType from result a,species b,primer c,seqtype d where a.SpeciesID=b.SpeciesID and b.SpeciesName=\"$SP\" and a.SequenceTypeID=d.SequenceTypeID and d.SequenceType=\"$ST\" and c.PrimerID=a.PrimerID and b.SpeciesName=\"$SP\" and d.SequenceType=\"$ST\"";						
							$result = $conn->query($query);
							$cnt1 = $result->num_rows;
							if ($result->num_rows > 0)
							{
?>
    <!--========================================
           Preloader
    ========================================-->
    <div class="page-preloader">
		
        <div class="spinner">
			Searching in progress. Please wait... </br>
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
    </div>
<?php								
								echo "<div>&nbsp;</div>";
								echo "<div id='SearchHead'>";
									echo "Search successful using keywords : <b>$SP</b> and <b>$ST</b> <br /><b>$cnt1</b> entries found.";
									echo "<div align='center'><a href='download.php'><b> Download Result File </b></a></div>";
								echo "</div>";
								echo "<div>&nbsp;</div>";			
								
								echo "<thead>";
								echo "<tr>";
								echo "<th>Species Name</th>";
								echo "<th>Sequence ID</th>";
								echo "<th>Sequence Type</th>";
								echo "<th>Primer Name</th>";
								echo "<th>Primer Type</th>";
								echo "<th>Product Size</th>";
								echo "<th>ForwardSCAR</th>";
								echo "<th>ReverseSCAR</th>";							
								echo "</tr>";
								echo "</thead>";
								
								echo "<tbody>";
								while ($row = $result->fetch_assoc())
								{								
									$SQID = '';
									$SQTyp = '';
									$PName = '';
									$PType = '';
									$PSize = '';
									$FScar = '';
									$RScar = '';
									foreach($row as $ky=>$val)
									{
										if($ky == 'SequenceID')
										{
											$SPID = $val;
										}
										if($ky == 'SequenceType')
										{
											$SQTyp = $val;
										}
										if($ky == 'PrimerName')
										{
											$PName = $val;
										}
										if($ky == 'PrimerType')
										{
											$PType = $val;
										}
										if($ky == 'ProductSize')
										{
											$PSize = $val;
										}
										if($ky == 'ForwardSCAR')
										{
											$FScar = $val;
										}
										if($ky == 'ReverseSCAR')
										{
											$RScar = $val;
										}
									}
									echo "<tr>";
										echo "<td>$SP</td>";
										echo "<td>$SPID</td>";
										echo "<td>$SQTyp</td>";
										echo "<td>$PName</td>";
										echo "<td>$PType</td>";
										echo "<td>$PSize</td>";
										echo "<td>$FScar</td>";
										echo "<td>$RScar</td>";
									echo "</tr>";

									//Reopen the file in append mode
									$RF = fopen($ResultFile,'a') or die ("Cannot open file!");
									$Content= "$SP\t$SPID\t$SQTyp\t$PName\t$PType\t$PSize\t$FScar\t$RScar\n";
									fwrite($RF,$Content);
									fclose($RF);
									//close file									
								}
								echo "</tbody>";
							}
							else
							{
								echo "<div>&nbsp;</div>";
								echo "<div id='SearchHead'>";
									echo "No entries found.";
								echo "</div>";
								echo "<div>&nbsp;</div>";
							}
						}
						elseif($FieldsId == 'sp')
						{
// species only search start
							//Open a new file (w mode) for writing results - heading writing
							$ResultFile = 'Result.txt';
							$RF = fopen($ResultFile,'w') or die ("Cannot open file!");
							$Heading = "Species Name\tSequence ID\tSequence Type\tPrimer Name\tPrimer Type\tProduct Size\tForwardSCAR\tReverseSCAR\n\n";
							fwrite($RF,$Heading);
							fclose($RF);
							//close the file

							// Search using species name
							$query = "select a.SequenceID,a.ProductSize,a.ForwardSCAR,a.ReverseSCAR,c.PrimerName,c.PrimerType,d.SequenceType from result a,species b,primer c,seqtype d where a.SpeciesID=b.SpeciesID and b.SpeciesName=\"$SP\" and a.SequenceTypeID=d.SequenceTypeID and c.PrimerID=a.PrimerID and b.SpeciesName=\"$SP\"";
							
							$result = $conn->query($query);
							$cnt1 = $result->num_rows;
							if ($result->num_rows > 0)
							{
?>
    <!--========================================
           Preloader
    ========================================-->
    <div class="page-preloader">
		
        <div class="spinner">
			Searching in progress. Please wait... </br>
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
    </div>
<?php									
								echo "<div>&nbsp;</div>";
								echo "<div id='SearchHead'>";
									echo "Searched using keyword(s) : <b>$SP</b> and <b>$ST</b> <br /><b>$cnt1</b> entries found.";
									echo "<div align='center'><a href='download.php'><b> Download Result File </b></a></div>";
								echo "</div>";
								echo "<div>&nbsp;</div>";			
								
								echo "<thead>";
								echo "<tr>";
								echo "<th>Species Name</th>";
								echo "<th>Sequence ID</th>";
								echo "<th>Sequence Type</th>";
								echo "<th>Primer Name</th>";
								echo "<th>Primer Type</th>";
								echo "<th>Product Size</th>";
								echo "<th>ForwardSCAR</th>";
								echo "<th>ReverseSCAR</th>";							
								echo "</tr>";
								echo "</thead>";
								
								echo "<tbody>";
								while ($row = $result->fetch_assoc())
								{								
									$SQID = '';
									$SQTyp = '';
									$PName = '';
									$PType = '';
									$PSize = '';
									$FScar = '';
									$RScar = '';
									foreach($row as $ky=>$val)
									{
										if($ky == 'SequenceID')
										{
											$SPID = $val;
										}
										if($ky == 'SequenceType')
										{
											$SQTyp = $val;
										}
										if($ky == 'PrimerName')
										{
											$PName = $val;
										}
										if($ky == 'PrimerType')
										{
											$PType = $val;
										}
										if($ky == 'ProductSize')
										{
											$PSize = $val;
										}
										if($ky == 'ForwardSCAR')
										{
											$FScar = $val;
										}
										if($ky == 'ReverseSCAR')
										{
											$RScar = $val;
										}
									}
									echo "<tr>";
										echo "<td>$SP</td>";
										echo "<td>$SPID</td>";
										echo "<td>$SQTyp</td>";
										echo "<td>$PName</td>";
										echo "<td>$PType</td>";
										echo "<td>$PSize</td>";
										echo "<td>$FScar</td>";
										echo "<td>$RScar</td>";
									echo "</tr>";

									//Reopen the file in append mode
									$RF = fopen($ResultFile,'a') or die ("Cannot open file!");
									$Content= "$SP\t$SPID\t$SQTyp\t$PName\t$PType\t$PSize\t$FScar\t$RScar\n";
									fwrite($RF,$Content);
									fclose($RF);
									//close file									
								}
								echo "</tbody>";
							}
							else
							{
								echo "<div>&nbsp;</div>";
								echo "<div id='SearchHead'>";
									echo "No entries found.";
								echo "</div>";
								echo "<div>&nbsp;</div>";
							}							
// species only search end
						}
						elseif($FieldsId == 'st')
						{
// seqtype only search start
							//Open a new file (w mode) for writing results - heading writing
							$ResultFile = 'Result.txt';
							$RF = fopen($ResultFile,'w') or die ("Cannot open file!");
							$Heading = "Species Name\tSequence ID\tSequence Type\tPrimer Name\tPrimer Type\tProduct Size\tForwardSCAR\tReverseSCAR\n\n";
							fwrite($RF,$Heading);
							fclose($RF);
							//close the file

							// Search using species name
							$query = "select a.SequenceID,a.ProductSize,a.ForwardSCAR,a.ReverseSCAR,b.SpeciesName,c.PrimerName,c.PrimerType,d.SequenceType from result a,species b,primer c,seqtype d where a.SpeciesID=b.SpeciesID and a.SequenceTypeID=d.SequenceTypeID and d.SequenceType=\"$ST\" and c.PrimerID=a.PrimerID and d.SequenceType=\"$ST\"";	
							
							$result = $conn->query($query);
							$cnt1 = $result->num_rows;
							if ($result->num_rows > 0)
							{
?>
    <!--========================================
           Preloader
    ========================================-->
    <div class="page-preloader">
		
        <div class="spinner">
			Searching in progress. Please wait... </br>
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
    </div>
<?php									
								echo "<div>&nbsp;</div>";
								echo "<div id='SearchHead'>";
									echo "Searched using keyword(s) : <b>$SP</b> and <b>$ST</b> <br /><b>$cnt1</b> entries found.";
									echo "<div align='center'><a href='download.php'><b> Download Result File </b></a></div>";
								echo "</div>";
								echo "<div>&nbsp;</div>";			
								
								echo "<thead>";
								echo "<tr>";
								echo "<th>Species Name</th>";
								echo "<th>Sequence ID</th>";
								echo "<th>Sequence Type</th>";
								echo "<th>Primer Name</th>";
								echo "<th>Primer Type</th>";
								echo "<th>Product Size</th>";
								echo "<th>ForwardSCAR</th>";
								echo "<th>ReverseSCAR</th>";							
								echo "</tr>";
								echo "</thead>";
								
								echo "<tbody>";
								while ($row = $result->fetch_assoc())
								{								
									$SPName = '';
									$SQID = '';
									$SQTyp = '';
									$PName = '';
									$PType = '';
									$PSize = '';
									$FScar = '';
									$RScar = '';
									foreach($row as $ky=>$val)
									{
										if($ky == 'SequenceID')
										{
											$SPID = $val;
										}
										if($ky == 'SpeciesName')
										{
											$SPName = $val;
										}										
										if($ky == 'SequenceType')
										{
											$SQTyp = $val;
										}
										if($ky == 'PrimerName')
										{
											$PName = $val;
										}
										if($ky == 'PrimerType')
										{
											$PType = $val;
										}
										if($ky == 'ProductSize')
										{
											$PSize = $val;
										}
										if($ky == 'ForwardSCAR')
										{
											$FScar = $val;
										}
										if($ky == 'ReverseSCAR')
										{
											$RScar = $val;
										}
									}
									echo "<tr>";
										echo "<td>$SPName</td>";
										echo "<td>$SPID</td>";
										echo "<td>$SQTyp</td>";
										echo "<td>$PName</td>";
										echo "<td>$PType</td>";
										echo "<td>$PSize</td>";
										echo "<td>$FScar</td>";
										echo "<td>$RScar</td>";
									echo "</tr>";

									//Reopen the file in append mode
									$RF = fopen($ResultFile,'a') or die ("Cannot open file!");
									$Content= "$SPName\t$SPID\t$SQTyp\t$PName\t$PType\t$PSize\t$FScar\t$RScar\n";
									fwrite($RF,$Content);
									fclose($RF);
									//close file									
								}
								echo "</tbody>";
							}
							else
							{
								echo "<div>&nbsp;</div>";
								echo "<div id='SearchHead'>";
									echo "No entries found.";
								echo "</div>";
								echo "<div>&nbsp;</div>";
							}							
// seqtype only search end
						}
					?>
		</table>
	</div>
	</div>
	
	<!-- content -->
	
    <!--========================================
           Footer
    ========================================-->
    <footer>
        <div>
            <div align="center">
                <div >&copy;2018 All rights reserved</div>
            </div>
        </div>
    </footer>
	
    <script src="jquery/main.js"></script>	
</BODY>
</HTML>
