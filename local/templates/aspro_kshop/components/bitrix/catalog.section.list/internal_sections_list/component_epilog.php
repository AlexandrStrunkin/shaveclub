<?global $KShopSectionID;?>
<script type="text/javascript">
$(document).ready(function() {
	var KShopSectionID = '<?=($KShopSectionID ? $KShopSectionID : 0)?>';
	$('.internal_sections_list .cur').removeClass('cur');
	$('*[data-id="'+ KShopSectionID + '"]').addClass('cur').parents('.child_container').parent().addClass('cur');
});
</script>