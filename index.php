<!DOCTYPE html>
<html lang="en">
<head>
<body>
                <div class="col-md-5 col-md-offset-1">
						<?php
						include('dbconfig.php');
						echo "<select  id='species' name='species' class='ui-autocomplete-input form-control' >";
						$sqlSP = "select * from species order by SpeciesID"; // FETCH
						$result = $conn->query($sqlSP);
						while ($row = $result->fetch_assoc())
						{
							unset($id, $name);
							echo "<option value=\"".$row['SpeciesName']."\">".$row['SpeciesName']."</option>";
						}
						echo "</select>";
						$conn->close();
						?>
						<!-- DROPDOWN LIST AREA END -->
                        <div class="form-group text-center">
                        </div>
                    </form>