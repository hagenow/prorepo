                <input type="text" id="catsearch" autocomplete="off">

                <!-- Show Results -->
                <h4 id="results-text">Showing results for: <b id="catsearch-string">Array</b></h4>
                <ul id="results"></ul>

<hr>

<form name="CreateCategory" id="CreateCategory" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=cat2">
    <input type="text" name="catname" id="catname" value="Name der Kategorie" /><br />
    <input type="submit" name="submitbutton" id="submitbutton" value="Datei hochladen">
</form>
