<?php
global $user_id;
$query = "select * from POST_SN where user_id='$user_id'";
$result = pg_query($con,$query);

//Get the total records count
$total_posts = pg_num_rows($result);
//USe cel to get the whole number_format
$total_pages = ceil($total_posts/$per_page);

//Now we handle the first page
echo "
<center>
<div id='pagination'>
<a href='home.php?page=1'>First Page </a>
";

for($i=1;$i<=$total_pages;$i++){
	echo "<a href='home.php?page$i'> $i </a>";
}

//Handle tehlasat page
echo "<a href='home.php?page=$total_pages'> Last Page</a></center></div>";
?>