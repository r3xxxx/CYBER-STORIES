<nav>
    <ol>
        
        <?php
        print '<li class="';
        if ($path_parts['filename'] == "home") {
            print ' activePage ';
        }
        print '">';
        print '<a href="home.php">Home</a>';
        print '</li>';
        
        
        print '<li class="';
        if ($path_parts['filename'] == "champ") {
            print ' activePage ';
        }
        print '">';
        print '<a href="champ.php">Champ the Whale</a>';
        print '</li>';

        print '<li class="';
        if ($path_parts['filename'] == "chicken") {
            print ' activePage ';
        }
        print '">';
        print '<a href="chicken.php">Chicken</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "cows") {
            print ' activePage ';
        }
        print '">';
        print '<a href="cows.php">Speed the Cow</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "goat") {
            print ' activePage ';
        }
        print '">';
        print '<a href="goat.php">Gassy the Goat</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "horse") {
            print ' activePage ';
        }
        print '">';
        print '<a href="horse.php">Happy the Horse</a>';
        print '</li>';
        
        
        
        print '<li class="';
        if ($path_parts['filename'] == "FEEDBACK") {
            print ' activePage ';
        }
        print '">';
        print '<a href="finalForm.php">Submit yours! </a>';
        print '</li>';
        ?>
    </ol>
</nav>