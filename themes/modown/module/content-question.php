<?php 
global $post_target;
?>
<div class="post question list noimg">
  <div class="con">
    <a target="_blank" href="<?php the_permalink();?>" class="question-avatar"><?php echo get_avatar(get_the_author_meta( 'ID' ));?></a>
    <h3 itemprop="name headline"><a itemprop="url" rel="bookmark" href="<?php the_permalink();?>" title="<?php the_title();?>" target="<?php echo $post_target;?>"><?php the_title();?></a></h3>
    <div class="list-meta clearfix">
      <?php 
        $question_category = get_the_terms(get_the_ID(),'question_category');
        if($question_category){
          echo '<a href="'.get_term_link($question_category[0]).'" class="question-cat">'.$question_category[0]->name.'</a>';
        }
      ?>
      <span class="time"><i class="icon icon-time"></i> <?php echo MBThemes_timeago( MBThemes_post_date() ) ?></span><span class="views"><i class="icon icon-eye"></i> <?php MBThemes_views();?></span><span class="comments"><i class="icon icon-comment"></i> <?php echo get_comments_number('0', '1', '%');?></span>
    </div>
  </div>
</div>