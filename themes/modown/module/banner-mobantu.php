<div class="banner-mobantu">
	<div class="container">
      	<?php echo _MBT('banner_title')?('<h2>'._MBT('banner_title').'</h2>'):'';?>
        <?php echo _MBT('banner_desc')?('<p>'._MBT('banner_desc').'</p>'):'';?>
        <?php if(_MBT('banner_btn')){?><a class="custom-btn" href="<?php echo _MBT('banner_link');?>" target="_blank"><?php echo _MBT('banner_btn');?></a><?php }?>
        <img src="<?php echo _MBT('banner_mobantu_img');?>" alt="<?php echo _MBT('banner_title');?>">
    </div>
</div>
<div class="banner-mobantu-service">
    <div class="container">
        <ul>
            <li>
                <i class="dripicons dripicons-<?php echo _MBT("banner_mobantu_icon1","web");?>"></i>
                <strong><?php echo _MBT("banner_mobantu_title1","专业WordPress开发");?></strong>
                <p><?php echo _MBT("banner_mobantu_desc1","10年PHP开发和WordPress建站经验，专业WordPress开发");?></p>
            </li>
            <li>
                <i class="dripicons dripicons-<?php echo _MBT("banner_mobantu_icon2","jewel");?>"></i>
                <strong><?php echo _MBT("banner_mobantu_title2","高端UI设计");?></strong>
                <p><?php echo _MBT("banner_mobantu_desc2","独特大气的WEB UI设计，让您的网站脱颖而出，留住客户");?></p>
            </li>
            <li>
                <i class="dripicons dripicons-<?php echo _MBT("banner_mobantu_icon3","monitor");?>"></i>
                <strong><?php echo _MBT("banner_mobantu_title3","响应式布局");?></strong>
                <p><?php echo _MBT("banner_mobantu_desc3","兼容极佳的自适应布局，优化PC、Pad和Mobile端用户体验");?></p>
            </li>
            <li>
                <i class="dripicons dripicons-<?php echo _MBT("banner_mobantu_icon4","heart");?>"></i>
                <strong><?php echo _MBT("banner_mobantu_title4","优质服务");?></strong>
                <p><?php echo _MBT("banner_mobantu_desc4","响应及时的在线工单，解决您网站的各种问题，让您建站无忧");?></p>
            </li>
        </ul>
    </div>
</div>