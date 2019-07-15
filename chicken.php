
<?php

include "top.php";

?>

<figure>
    <img src="../images/chicken.JPG" alt="chicken">
    <figcaption>Picture by Lowell Deschenes</figcaption>
</figure>

<?php
print "<h1>" . $stories[1][0] . "</h1>";
print "<p>"  . $stories[1][1] . "</p>";

?>     

<img class="bannerleft" src="../images/orange.jpeg" alt="orange swirls">
<img class="bannerright" src="../images/orange.jpeg" alt="orange swirls">


<?php

include ("footer.php");
?>