
<?php

include "top.php";

?>

<figure>
    <img src="../images/whale.jpeg" alt="whale">
    <figcaption>Picture by Lowell Deschenes</figcaption>
</figure>

<?php
print "<h1>" . $stories[4][0] . "</h1>";
print "<p>" . $stories[4][1] . "</p>";
?>     

<img class="bannerleft" src="../images/red.png" alt="red swirls">
<img class="bannerright" src="../images/red.png" alt="red swirls">


<?php

include ("footer.php");
?>