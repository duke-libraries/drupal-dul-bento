<?php

if($queryTerms != "") {

?>

  <div class="col-md-12 well more-search-options">

    <h2>More Search Options for "<?php echo $queryDisplay; ?>"</h2>

      <ul>
        <li><a href="http://guides.library.duke.edu/az.php?q=<?php echo $queryDisplay; ?>">Research Databases</a></li>
        <li><a href="http://getitatduke.library.duke.edu/ejp/?libHash=PM6MT7VG3J#/search/?searchControl=title&searchType=title_contains&criteria=<?php echo $queryDisplay; ?>&language=en-US&titleType=JOURNALS">Online Journal Titles</a></li>
        <li><a href="http://library.duke.edu/find">Search &amp; Find</a></li>
        <li><a href="https://repository.duke.edu/catalog?utf8=%E2%9C%93&q=<?php echo $queryDisplay; ?>&search_field=all_fields">Duke Digital Repository</a></li>
        <li><a href="http://proxy.lib.duke.edu/login?url=http://firstsearch.oclc.org/FSIP/FSPrefs?dbname=WorldCat:pagename=search">WorldCat</a></li>
        <li><a href="https://scholar.google.com/scholar?hl=en&q=<?php echo $queryDisplay; ?>&btnG=&as_sdt=1%2C34&as_sdtp=">Google Scholar</a></li>
      </ul>

  </div>

<?php

}

?>
