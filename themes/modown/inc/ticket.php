<?php
function createNewTicket($uid,$type,$number,$note,$content,$image,$email){
	global $wpdb;
	$result = $wpdb->query("insert into ".$wpdb->prefix . "tickets(user_id,type,number,email,create_time,note) values($uid,$type,'".esc_sql($number)."','".$email."','".date("Y-m-d H:i:s")."','".$note."')");
	if($result){ 
		$result2 = $wpdb->query("insert into ".$wpdb->prefix . "ticket_item(user_id,ticket_id,type,image,create_time,note) values($uid,".getTicketIdByNum($number).",1,'".$image."','".date("Y-m-d H:i:s")."','".$content."')");
		if($result2){
			return true;
		}
	}
	return false;
}

function createNewTicketByAdmin($uid,$type,$number,$note,$content,$image,$email){
	global $wpdb,$current_user;
	$result = $wpdb->query("insert into ".$wpdb->prefix . "tickets(user_id,type,number,email,create_time,note,status) values($uid,$type,'".esc_sql($number)."','".$email."','".date("Y-m-d H:i:s")."','".$note."',1)");
	if($result){ 
		$result2 = $wpdb->query("insert into ".$wpdb->prefix . "ticket_item(user_id,ticket_id,type,image,create_time,note) values($current_user->ID,".getTicketIdByNum($number).",2,'".$image."','".date("Y-m-d H:i:s")."','".$content."')");
		if($result2){
			return true;
		}
	}
	return false;
}

function createNewReply($uid,$type,$number,$content,$image){
	global $wpdb;
	$result = $wpdb->query("insert into ".$wpdb->prefix . "ticket_item(user_id,ticket_id,type,image,create_time,note) values($uid,".getTicketIdByNum($number).",$type,'".$image."','".date("Y-m-d H:i:s")."','".$content."')");
	if($result){
		$wpdb->query("update ".$wpdb->prefix . "tickets set status=0 where number='".esc_sql($number)."'");
		return true;
	}
	return false;
}

function createNewReplyByAdmin($uid,$type,$number,$content,$image){
	global $wpdb;
	$result = $wpdb->query("insert into ".$wpdb->prefix . "ticket_item(user_id,ticket_id,type,image,create_time,note) values($uid,".getTicketIdByNum($number).",$type,'".$image."','".date("Y-m-d H:i:s")."','".$content."')");
	if($result){
		$wpdb->query("update ".$wpdb->prefix . "tickets set status=1 where number='".esc_sql($number)."'");
		return true;
	}
	return false;
}

function getTicketIdByNum($number){
	global $wpdb;
	$result = $wpdb->get_var( $wpdb->prepare(
            "SELECT id FROM ".$wpdb->prefix ."tickets WHERE number = %s",
            esc_sql($number) ) );
	if($result > 0) return $result;
	return 0;
}

function getTicketByNum($number){
	global $wpdb;
	$result = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM ".$wpdb->prefix ."tickets WHERE number = %s",
            esc_sql($number) ) );
	return $result;
}

function checkTicketByNum($number){
	global $wpdb;
	$result = $wpdb->get_row( $wpdb->prepare(
            "SELECT id FROM ".$wpdb->prefix ."tickets WHERE number = %s",
            esc_sql($number) ) );
	if($result) return true;
	else return false;
}

function checkTicketIsMine($number,$uid){
	global $wpdb;
	if(current_user_can('administrator')){
		return true;
	}else{
		$result = $wpdb->get_row( $wpdb->prepare(
            "SELECT id FROM ".$wpdb->prefix ."tickets WHERE number = %s and user_id = %d",
            esc_sql($number), $uid ) );
		if($result) return true;
		else return false;
	}
}

function checkTicketIsClosed($number){
	global $wpdb;
	$result = $wpdb->get_row( $wpdb->prepare(
            "SELECT id FROM ".$wpdb->prefix ."tickets WHERE number = %s and status = 3",
            esc_sql($number) ) );
	if($result) return true;
	else return false;
}

function checkTicketCreateIsFast($uid){
	global $wpdb;
	$result = $wpdb->get_row( $wpdb->prepare(
            "SELECT id FROM ".$wpdb->prefix ."tickets WHERE status < 2 and user_id = %d",
            $uid ) );
	if($result) return '1';
	else return '0';
}

function getTicketTypeNameByNum($number){
	global $wpdb;
	$result = $wpdb->get_var( $wpdb->prepare(
            "SELECT type FROM ".$wpdb->prefix ."tickets WHERE number = %s",
            esc_sql($number) ) );
	if($result == '1') return __('售前咨询','mobantu');
	elseif($result == '2') return __('售后服务','mobantu');
	elseif($result == '11') return __('私信','mobantu');
	else return __('其他','mobantu');
}

function getTicketStatusNameByNum($number){
	global $wpdb;
	$result = $wpdb->get_var( $wpdb->prepare(
            "SELECT status FROM ".$wpdb->prefix ."tickets WHERE number = %s",
            esc_sql($number) ) );
	if($result == '0') return '<font color="#EAB563">'.__('等待回复','mobantu').'</font>';
	elseif($result == '1') return '<font color="red">'.__('已回复','mobantu').'</font>';
	elseif($result == '2') return '<font color="#3FCF51">'.__('已完成','mobantu').'</font>';
	elseif($result == '3') return __('已关闭','mobantu');
	else return __('其他','mobantu');
}

function updateTicketSolved($number){
	global $wpdb;
	$result = $wpdb->update( $wpdb->prefix . "tickets" , array('status' => 2), array("number"=>esc_sql($number)) );
	if($result) return true;
	return false;
}

function updateTicketClosed($number){
	global $wpdb;
	$result = $wpdb->update( $wpdb->prefix . "tickets" , array('status' => 3), array("number"=>esc_sql($number)) );
	if($result) return true;
	return false;
}

function createTicketNum(){
	$value = '1';
	for($i=0;$i<10;$i++){
		$value .= rand(0,9);
	}
	return $value;
}

function modown_ticket_new_html(){
	global $wpdb, $current_user;
	$uid = $current_user->ID;
	if(isset($_GET['id']) && $_GET['id']){
		$number = esc_sql(trim($_GET['id']));
		if(checkTicketByNum($number) && checkTicketIsMine($number,$uid)){
		echo '<div class="ticket-item">
                <h3>'.__('工单：','mobantu').$number.'</h3>
                <div class="ticket-item-info">
                    '.get_user_by('id',getTicketByNum($number)->user_id)->user_login.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.getTicketTypeNameByNum($number).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.getTicketByNum($number)->create_time.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.getTicketStatusNameByNum($number).'
                    <input type="hidden" id="ticket-id" value="'.$number.'">
                </div>
                <div class="ticket-item-content">';
                $ticket_items = $wpdb->get_results("select * from ".$wpdb->prefix . "ticket_item where ticket_id=".getTicketIdByNum($number));
                if($ticket_items){
                    foreach($ticket_items as $item){  
                        echo    '<dl '.($item->type == '2' ? 'class="ticket-item-reply"' : '').'>
                                <dt><img src="'.MBThemes_get_avatar($item->user_id).'" class="avatar"></dt>
                                <dd>
                                '.str_replace("\n","<br>",$item->note);
                        $imgs = explode(',',$item->image);
                        $length = count($imgs);
                        if($item->image){
                            echo            '<div class="thumbs">';
                                    for($i=0;$i < $length;$i++){
                                        echo '<img class="ticket-thumb" src="'.$imgs[$i].'">';
                                    }
                                    
                            echo            '</div>';
                        }   
                        echo        '<time>'.$item->create_time.'</time>
                                </dd>
                            </dl>';
                    }
                }
                echo '</div>';
                if(getTicketByNum($number)->status < 2){
                    echo '<div class="ticket-item-status">
                        '.__('如果您的问题已得到解决，请点击：','mobantu').'<a href="javascript:;" class="btn btn-danger ticket-solved">'.__('已解决','mobantu').'</a>';
                    if(current_user_can('administrator')) echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:;" class="btn btn-primary ticket-close">'.__('关闭工单','mobantu').'</a>';
                    echo '</div>
                    <form class="ticket-reply">
                        <h4>'.__('继续回复：','mobantu').'</h4>
                        <p style="margin-bottom:20px;"><textarea name="content" id="content" class="form-control" id="" cols="30" rows="6" placeholder="'.__('请输入内容...','mobantu').'" style="height: inherit;"></textarea><span style="font-size: 13px;">'.sprintf(__('图片可上传到 %s 后在内容里贴上链接','mobantu'), '<a href="https://imgchr.com/" target="_blank" style="text-decoration:underline;">'.__('图床','mobantu').'</a>').'</span></p>';
                        if(_MBT('ticket_img')){
	                        echo '<div class="pics">
	                            <div class="pic pic-no ticket-upload">
	                                <i class="dripicons dripicons-plus"></i>
	                            </div>
	                            <div class="pic pic-no ticket-upload">
	                                <i class="dripicons dripicons-plus"></i>
	                            </div>
	                            <div class="pic pic-no ticket-upload">
	                                <i class="dripicons dripicons-plus"></i>
	                            </div>
	                        </div>
	                        <input class="ticket-file" type="file" style="display:none" accept="image/gif, image/jpeg, image/png">';
	                    }
                        echo '<div class="hdl">
                            <input type="button" class="btn btn-primary ticket-reply-submit" name="submit" value="'.__('提交回复','mobantu').'">
                            <input type="hidden" name="id" value="'.$number.'">
                            <p class="text-muted">'.__('我们将尽快答复您，请耐心等待！','mobantu').'</p>
                        </div>
                    </form>';
                }
                
            echo '</div>';
        }

	}else{
?>
<form class="ticket-new">
    <h3><?php _e('提交新工单：','mobantu')?></h3>
    <ul class="user-meta">
        <li>
            <label class="tit"><?php _e('工单类别：','mobantu')?></label>
            <span class="radio-inline"><input name="item" value="1" type="radio"><?php _e('售前咨询','mobantu')?></span>
            <span class="radio-inline"><input name="item" value="2" type="radio"><?php _e('售后服务','mobantu')?></span>
            <span class="radio-inline"><input name="item" value="3" type="radio" checked><?php _e('其他','mobantu')?></span>
        </li>
        <li>
            <label class="tit"><?php _e('工单内容：','mobantu')?></label>
            <textarea name="content" class="form-control" id="" cols="30" rows="8" placeholder="<?php _e('请输入内容...','mobantu')?>" style="max-width: 100%;padding: 6px 12px;height: inherit;"></textarea>
            <p style="font-size: 13px;"><?php echo sprintf(__('图片可上传到 %s 后在内容里贴上链接','mobantu'), '<a href="https://imgchr.com/" target="_blank" style="text-decoration:underline;">'.__('图床','mobantu').'</a>');?></p>
        </li>
        <li>
            <label class="tit"><?php _e('联系邮箱：','mobantu')?></label>
            <input type="email" name="email" class="form-control" id="email" placeholder="<?php _e('请输入邮箱...','mobantu')?>" style="width:30%" value="<?php echo $current_user->user_email;?>" />
        </li>
        <?php if(_MBT('ticket_img')){?>
        <li>
            <label class="tit"><?php _e('添加图片：','mobantu')?></label>
            <div class="pics">
                <div class="pic pic-no ticket-upload">
                    <i class="dripicons dripicons-plus"></i>
                </div>
                <div class="pic pic-no ticket-upload">
                    <i class="dripicons dripicons-plus"></i>
                </div>
                <div class="pic pic-no ticket-upload">
                    <i class="dripicons dripicons-plus"></i>
                </div>
                <input class="ticket-file hide" type="file" style="display: none;" accept="image/gif, image/jpeg, image/png">
            </div>
        </li>
    	<?php }?>
        <li>
            <input type="button" class="btn btn-primary ticket-new-submit" name="submit" value="<?php _e('提交工单','mobantu')?>" style="float: right;">
            <br><br>
            <p style="float: right;margin-top: -25px;font-size:12px;"><?php _e('我们将尽快答复您，请耐心等待！','mobantu')?></p>
        </li>
    </ul>
</form>
<?php
	}
}

function modown_ticket_list_html(){
	global $wpdb, $current_user;
	$uid = $current_user->ID;
	if(current_user_can('administrator')){
        $total_trade   = $wpdb->get_var("SELECT COUNT(id) FROM ".$wpdb->prefix . "tickets");
    }else{
        $total_trade   = $wpdb->get_var("SELECT COUNT(id) FROM ".$wpdb->prefix . "tickets WHERE user_id=".$uid);
    }
    $perpage = 15;
    if (!get_query_var('paged')) {
		$paged = 1;
	}else{
		$paged = get_query_var('paged');
	}
    $pagess = ceil($total_trade / $perpage);
    $offset = $perpage*($paged-1);
    if(current_user_can('administrator')){
        $lists = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "tickets order by create_time DESC limit $offset,$perpage");
    }else{
        $lists = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "tickets where user_id=".$uid." order by create_time DESC limit $offset,$perpage");
    }
    
?>
	<table class="table table-striped table-hover user-tickets"><thead><tr><th class=pc><?php _e('工单ID','mobantu')?></th><th><?php _e('内容','mobantu')?></th><th class=pc><?php _e('类别','mobantu')?></th><th class=pc><?php _e('时间','mobantu')?></th><th><?php _e('状态','mobantu')?></th></tr></thead><tbody>
		<?php foreach ($lists as $ticket) {?>
		<tr>
			<td class=pc><?php echo $ticket->number;?></td>
			<td><a href="<?php echo add_query_arg(array("action"=>"ticket","id"=>$ticket->number),get_permalink(MBThemes_page("template/user.php")));?>"><?php echo $ticket->note;?></a></td>
			<td class=pc><?php echo getTicketTypeNameByNum($ticket->number);?></td>
			<td class=pc><?php echo $ticket->create_time;?></td>
			<td><?php echo getTicketStatusNameByNum($ticket->number);?></td>
		</tr>
		<?php }?>
	</tbody></table>
	<?php MBThemes_custom_paging($paged,$pagess);?>
<?php
}

if(_MBT('ticket')){
	add_action('admin_menu', 'modown_ticket_menu');
}
function modown_ticket_menu() {
	add_menu_page(__('工单','mobantu'), __('工单','mobantu'), 'activate_plugins', 'modown_ticket_list', 'modown_ticket_list','dashicons-format-status');
}

function modown_ticket_list(){
	global $wpdb;
	if(isset($_POST['delid']) && $_POST['delid'] > 0){
		$wpdb->query("delete from ".$wpdb->prefix . "tickets where id=".$_POST['delid']);
	}

	$total_trade   = $wpdb->get_var("SELECT COUNT(id) FROM ".$wpdb->prefix . "tickets");
	$ice_perpage = 20;
	$pages = ceil($total_trade / $ice_perpage);
	$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
	$offset = $ice_perpage*($page-1);
?>
<div class="wrap">
    <?php
   		$adds=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "tickets order by id DESC limit $offset,$ice_perpage");
    ?>
    <h3>工单列表</h3>

    <table class="widefat striped" style="width:100%;">
        <thead>
        <tr>
			<th width="10%"><?php _e('工单ID','mobantu')?></th> 
            <th width="30%"><?php _e('内容','mobantu')?></th>
            <th width="10%"><?php _e('用户','mobantu')?></th>
            <th width="20%"><?php _e('时间','mobantu')?></th>
            <th width="10%"><?php _e('状态','mobantu')?></th>
            <th width="10%"><?php _e('操作','mobantu')?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if($adds) {
            foreach($adds as $value)
            {
            	$cuu = get_user_by('id',$value->user_id);
                echo "<tr>\n";
				echo "<td>$value->number</td>";
                echo "<td><a href='".add_query_arg(array("action"=>"ticket","id"=>$value->number),get_permalink(MBThemes_page("template/user.php")))."' target='_blank'>$value->note</td>";
                echo "<td>".($cuu?$cuu->user_login:'')."</td>\n";
				echo "<td>$value->create_time</td>";
				echo "<td>".getTicketStatusNameByNum($value->number)."</td>";
				echo '<td><form method="post"><input type="hidden" name="delid" value="'.$value->id.'"><input type="submit" class="button" value="'.__('删除','mobantu').'"></form></td>';
                echo "</tr>";
            }
        }
        else
        {
            echo '<tr><td colspan="6" align="center"><strong>'.__('暂无记录','mobantu').'</strong></td></tr>';
        }
        ?>
        </tbody>
    </table>
    <?php echo modown_ticket_admin_pagenavi($total_trade,$ice_perpage);?>		
</div>
<?php
}
	
function modown_ticket_admin_pagenavi($total_count, $number_per_page=15){

	$current_page = isset($_GET['paged'])?$_GET['paged']:1;

	if(isset($_GET['paged'])){
		unset($_GET['paged']);
	}

	$base_url = add_query_arg($_GET,admin_url('admin.php'));

	$total_pages	= ceil($total_count/$number_per_page);

	$first_page_url	= $base_url.'&amp;paged=1';
	$last_page_url	= $base_url.'&amp;paged='.$total_pages;
	
	if($current_page > 1 && $current_page < $total_pages){
		$prev_page		= $current_page-1;
		$prev_page_url	= $base_url.'&amp;paged='.$prev_page;

		$next_page		= $current_page+1;
		$next_page_url	= $base_url.'&amp;paged='.$next_page;
	}elseif($current_page == 1){
		$prev_page_url	= '#';
		$first_page_url	= '#';
		if($total_pages > 1){
			$next_page		= $current_page+1;
			$next_page_url	= $base_url.'&amp;paged='.$next_page;
		}else{
			$next_page_url	= '#';
		}
	}elseif($current_page == $total_pages){
		$prev_page		= $current_page-1;
		$prev_page_url	= $base_url.'&amp;paged='.$prev_page;
		$next_page_url	= '#';
		$last_page_url	= '#';
	}
	?>
	<div class="tablenav bottom">
		<div class="tablenav-pages">
			<span class="displaying-num">每页 <?php echo $number_per_page;?> 共 <?php echo $total_count;?></span>
			<span class="pagination-links">
				<a class="first-page button <?php if($current_page==1) echo 'disabled'; ?>" title="前往第一页" href="<?php echo $first_page_url;?>">«</a>
				<a class="prev-page button <?php if($current_page==1) echo 'disabled'; ?>" title="前往上一页" href="<?php echo $prev_page_url;?>">‹</a>
				<span class="paging-input">第 <?php echo $current_page;?> 页，共 <span class="total-pages"><?php echo $total_pages; ?></span> 页</span>
				<a class="next-page button <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往下一页" href="<?php echo $next_page_url;?>">›</a>
				<a class="last-page button <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往最后一页" href="<?php echo $last_page_url;?>">»</a>
			</span>
		</div>
		<br class="clear">
	</div>
	<?php
}

