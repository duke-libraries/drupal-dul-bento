<?php

  $trln_json = file_get_contents(__DIR__ . '/location_item_holdings.json');

  $json = json_decode($trln_json, true);

  $loc_b_json = $json['loc_b'];
  $loc_n_json = $json['loc_n'];

?>
