// Change labels
$('title').text(lang['title_dashboard'] + ' | ' + lang['site_name']);
$('h1').text(lang['title_dashboard']);
$('#label_traffic').html('<i class="fas fa-chart-line"></i>&nbsp;' + lang['label_traffic']);
$('#label_devices').html('<i class="fas fa-chart-line"></i>&nbsp;' + lang['label_devices']);
$('#label_new_orders').html('<i class="fas fa-dollar-sign"></i>&nbsp;' + lang['label_new_orders']);
$('#orders_list').html('<div class="spinner-box"><i class="fas fa-spinner fa-spin"></i></div>');