jQuery(function($){
	$(".modown-ticket-user-do").click(function(){
		var uid = $(this).data("uid");
	    layer.prompt({title: '发私信（创建工单）', formType: 2}, function(text, index){
	    	if(text){
	    		layer.msg('发送中...');
			  	$.post(
				ajaxurl,
				{
					content: text,
					uid: uid,
					action: "admin_ticket_user"
				},
				function (data) {
					if( data.error ){
	 					if( data.msg ){
	 						layer.msg(data.msg)
	 					}
	 					return
	 				}
					layer.msg('发送成功');
	 				layer.close(index);
				},'json');
			}
		});
	});

	$(".button#doaction").click(function(){
		if($("#bulk-action-selector-top").val() == 'mbt_qmsg'){
			var ids = '';
			jQuery("tbody .check-column input[type='checkbox']").each(function() {
				if (jQuery(this).is(':checked')) {
			      	ids += ',' + jQuery(this).val();
			  	}
			});
			ids = ids.substring(1);
			if (ids.length == 0) {
				layer.msg('请至少选择一个用户');
			} else {
				layer.prompt({title: '群发私信（创建工单）', formType: 2}, function(text, index){
			    	if(text){
			    		layer.msg('发送中...');
					  	$.post(
						ajaxurl,
						{
							content: text,
							uids: ids,
							action: "admin_ticket_user"
						},
						function (data) {
							if( data.error ){
			 					if( data.msg ){
			 						layer.msg(data.msg)
			 					}
			 					return
			 				}
							layer.msg('发送成功');
			 				layer.close(index);
						},'json');
					}
				});
			}
			return false;
		}
	});
});