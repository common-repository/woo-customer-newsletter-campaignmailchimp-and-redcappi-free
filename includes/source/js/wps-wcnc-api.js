var $ =jQuery.noConflict();
$("#wps-wcnc-cust-all-mc-btn").click(function(){
  $("#wps-wcnc-cust-all-mc").submit();
});

$("#wps-wcnc-cust-all-rc-btn").click(function(){
  $("#wps-wcnc-cust-all-rc").submit();
}); 

$(document).ready(function() {
  get_mc_lists();
  rcGetListId();
});
/*MailChimp Get List ID*/
$( "#wps_wcnc_mailchimp_key" ).keyup(function() {
    get_mc_lists();
});
function get_mc_lists(){
  if(!$( "#wps_wcnc_mailchimp_key" ).val()){
    return;
  }
  var data = {
    'action': 'wps_wcnc_get_mc_list',
    'dataType': "html",
    'api_key': $( "#wps_wcnc_mailchimp_key" ).val(),
  };
  $("#wps_wcnc_mailchimp_list_id").html('');
  $('.wpcWcncloaderDiv').show();
  jQuery.post(ajaxurl, data, function(response) {
      $("#wps_wcnc_mailchimp_list_id").html(response);
      $('.wpcWcncloaderDiv').hide();
  });
}

/* Get RedCappi ID */

$( "#wps_wcnc_redcappi_pub_key" ).keyup(function() {
    rcGetListId();
});
$( "#wps_wcnc_redcappi_pri_key" ).keyup(function() {
    rcGetListId();
});

function rcGetListId(){
  if(!$( "#wps_wcnc_redcappi_pub_key" ).val())
    return;
  if(!$( "#wps_wcnc_redcappi_pri_key" ).val())
    return;

  var data = {
      'action': 'wps_wcnc_rc_get_list_id',
      'dataType': "html",
      'pub_key': $( "#wps_wcnc_redcappi_pub_key" ).val(),
      'pri_key': $( "#wps_wcnc_redcappi_pri_key" ).val(),
    };

    $('.wpcWcncloaderDiv').show();
    jQuery.post(ajaxurl, data, function(response) {
        $("#wps_wcnc_redcappi_list_id").html('');
        $("#wps_wcnc_redcappi_list_id").append(response);
        $('.wpcWcncloaderDiv').hide();
    });
}
