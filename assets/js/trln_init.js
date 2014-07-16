
function ClearText(form){
  (document.forms["queryBox"].elements["Ntt"].value = "");
  (document.forms["queryBox"].elements["Ntt"].className = "catalog_search");
}
jQuery(document).ready(function(){
  jQuery('#Ntt').autosuggest({source: 'duke', autocompleteOptions: {width: 350}});
});
