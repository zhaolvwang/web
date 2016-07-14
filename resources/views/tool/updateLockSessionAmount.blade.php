<link rel="stylesheet" href="{{ asset('packages/fore/images/style.css') }}">
<script type="text/javascript" src="{{ asset('packages/fore/images/jquery.Spinner.js') }}"></script>
<script type="text/javascript">
$(document).on("click",".update_locked",function(){
	var parent = $(this).parent();
	var amount = parent.children('.Amount').val();
	var id = parent.attr('id');
	var qty = parent.attr('qty');
	updateLockSessionAmount(id, amount, qty, 'in');
});
$(document).on("blur",".update_locked_input",function(){
	var parent = $(this).parent();
	var amount = $(this).val();
	var id = parent.attr('id');
	var qty = parent.attr('qty');
	updateLockSessionAmount(id, amount, qty, 'amount');
});
function updateGoods(msg, id) {
	$(".goods_count").text(" "+msg.count+" ");
	$(".goods_all_weight").text(msg.all_weight);
	$(".goods_total").text(msg.total);
	if(id) {
		$("#tb_goods_weight_"+id).children('span').text(msg.weight);
		$("#tb_goods_sums_"+id).children('span').text(msg.sums);
	}
}
function updateLockSessionAmount(id, amount, qty, type) {
	/**
	 *	qty:现货供应的数量，应该是现存数量(入库数量-出库数量)-锁货数量
	 */
	$.ajax({
		type:'GET',
		url:'{{ url("ajax/set-lock-session") }}',
		data:'id='+id+'&amount='+amount+'&type='+type+"&qty="+qty,
		success:function(msg) {
			switch (msg.status) {
				case -1:
					location.reload();
					break;
				case 1:
					
					break;
				case 2:
					
					break;
				case 3:
					
					break;

				default:
					updateGoods(msg, id);
					break;
			}
		}
	});
}


</script>