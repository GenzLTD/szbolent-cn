<?php
add_action( 'init', 'create_modown_post_type' );
function create_modown_post_type() {
    $blog_name = _MBT('blog_name')?_MBT('blog_name'):__('博客','mobantu');
    register_post_type( 'blog',
        array(
            'labels' => array(
                'name' => $blog_name,
                'singular_name' => 'blog',
                'add_new' => sprintf(__('发布%s','mobantu'),$blog_name),
                'add_new_item' => sprintf(__('发布新%s','mobantu'),$blog_name),
                'edit' => __('编辑','mobantu'),
                'edit_item' => sprintf(__('编辑%s','mobantu'),$blog_name),
                'new_item' => sprintf(__('新%s','mobantu'),$blog_name),
                'view' => __('查看','mobantu'),
                'view_item' => sprintf(__('查看%s','mobantu'),$blog_name),
                'search_items' => sprintf(__('搜索%s','mobantu'),$blog_name),
                'not_found' => sprintf(__('暂无%s','mobantu'),$blog_name),
                'not_found_in_trash' => sprintf(__('回收站中没有%s。','mobantu'),$blog_name),
                'parent' => sprintf(__('父%s','mobantu'),$blog_name)
            ),
 			'rewrite' => array('slug' => 'blog','with_front' => false),
            'public' => true,
			'show_in_nav_menus' => true,
            'menu_position' => 9,
            'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments' ),
            'menu_icon' => 'dashicons-flag',
            'has_archive' => true,
            'capability_type' => 'post'
        )
    );

    register_taxonomy('blogs','blog',
        array(
            'label' => sprintf(__('%s分类','mobantu'),$blog_name),
            'rewrite' => array( 'slug' => 'blogs' ),
            'show_admin_column' => true,
            'hierarchical' => true,
        )
    );

    $question_name = _MBT('question_name')?_MBT('question_name'):__('问答','mobantu');
    register_post_type( 'question',
        array(
            'labels' => array(
                'name' => $question_name,
                'singular_name' => 'question',
                'add_new' => sprintf(__('发布%s','mobantu'),$question_name),
                'add_new_item' => sprintf(__('发布新%s','mobantu'),$question_name),
                'edit' => __('编辑','mobantu'),
                'edit_item' => sprintf(__('编辑%s','mobantu'),$question_name),
                'new_item' => sprintf(__('新%s','mobantu'),$question_name),
                'view' => __('查看','mobantu'),
                'view_item' => sprintf(__('查看%s','mobantu'),$question_name),
                'search_items' => sprintf(__('搜索%s','mobantu'),$question_name),
                'not_found' => sprintf(__('暂无%s','mobantu'),$question_name),
                'not_found_in_trash' => sprintf(__('回收站中没有%s。','mobantu'),$question_name),
                'parent' => sprintf(__('父%s','mobantu'),$question_name)
            ),
            'rewrite' => array('slug' => 'question','with_front' => false),
            'public' => true,
            'show_in_nav_menus' => true,
            'menu_position' => 7,
            'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments' ),
            'menu_icon' => 'dashicons-editor-help',
            'has_archive' => true,
            //'capability_type' => 'question'
        )
    );

    register_taxonomy('question_category','question',
        array(
            'label' => sprintf(__('%s分类','mobantu'),$question_name),
            'rewrite' => array( 'slug' => 'questions' ),
            'show_admin_column' => true,
            'hierarchical' => true,
        )
    );

    $topic_name = __( '专题', 'mobantu' );
    $topic_labels = array(
        'name' => $topic_name,
        'singular_name' => $topic_name,
        'search_items' =>  sprintf(__('搜索%s','mobantu'),$topic_name),
        'all_items' => sprintf(__('所有%s','mobantu'),$topic_name),
        'parent_item' => sprintf(__('父级%s','mobantu'),$topic_name),
        'parent_item_colon' => sprintf(__('父级%s:','mobantu'),$topic_name),
        'edit_item' => sprintf(__('编辑%s','mobantu'),$topic_name),
        'update_item' => sprintf(__('更新%s','mobantu'),$topic_name),
        'add_new_item' => sprintf(__('添加新%s','mobantu'),$topic_name),
        'new_item_name' => sprintf(__('新%s','mobantu'),$topic_name),
        'not_found' => sprintf(__('未找到%s。','mobantu'),$topic_name),
    );

    register_taxonomy('topic','post',array(
        'hierarchical' => true,
        'show_in_rest' => true,
        'labels' => $topic_labels
    ));

    $post_texonomys = _MBT('post_taxonomy');
    if($post_texonomys){
        $post_texonomys = explode('|', $post_texonomys);
        foreach ($post_texonomys as $post_texonomy) {
            $post_texonomy = explode(',', $post_texonomy);
            $labels = array(
                'name' => $post_texonomy[0],
                'singular_name' => $post_texonomy[0],
                'search_items' => sprintf(__('搜索%s','mobantu'),$post_texonomy[0]),
                'all_items' => sprintf(__('所有%s','mobantu'),$post_texonomy[0]),
                'parent_item' => sprintf(__('父级%s','mobantu'),$post_texonomy[0]),
                'parent_item_colon' => sprintf(__('父级%s:','mobantu'),$post_texonomy[0]),
                'edit_item' => sprintf(__('编辑%s','mobantu'),$post_texonomy[0]),
                'update_item' => sprintf(__('更新%s','mobantu'),$post_texonomy[0]),
                'add_new_item' => sprintf(__('添加新%s','mobantu'),$post_texonomy[0]),
                'new_item_name' => sprintf(__('新%s','mobantu'),$post_texonomy[0]),
                'not_found' => sprintf(__('未找到%s。','mobantu'),$post_texonomy[0]),
            );

            register_taxonomy($post_texonomy[1],'post',array(
                'hierarchical' => true,
                'show_in_rest' => true,
                'labels' => $labels
            ));
        }
    }
}

add_action( 'restrict_manage_posts', 'mbt_post_filter_by_taxonomies' , 10, 2);
function mbt_post_filter_by_taxonomies( $post_type, $which ) {
    if ( 'post' !== $post_type )
        return;
    $taxonomies = array( 'topic' );
    foreach ( $taxonomies as $taxonomy_slug ) {
        $taxonomy_obj = get_taxonomy( $taxonomy_slug );
        $taxonomy_name = $taxonomy_obj->labels->name;
        $terms = get_terms( $taxonomy_slug );
        echo "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
        echo '<option value="">' . sprintf(__('所有%s','mobantu'), $taxonomy_name) . '</option>';
        foreach ( $terms as $term ) {
            printf(
                '<option value="%1$s" %2$s>%3$s</option>',
                $term->slug,
                ( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
                $term->name
            );
        }
        echo '</select>';
    }
}

add_action( 'restrict_manage_posts', 'mbt_blog_filter_by_taxonomies' , 10, 2);
function mbt_blog_filter_by_taxonomies( $post_type, $which ) {
    if ( 'blog' !== $post_type )
        return;
    $taxonomies = array( 'blogs' );
    foreach ( $taxonomies as $taxonomy_slug ) {
        $taxonomy_obj = get_taxonomy( $taxonomy_slug );
        $taxonomy_name = $taxonomy_obj->labels->name;
        $terms = get_terms( $taxonomy_slug );
        echo "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
        echo '<option value="">' .sprintf(__('所有%s','mobantu'), $taxonomy_name). '</option>';
        foreach ( $terms as $term ) {
            printf(
                '<option value="%1$s" %2$s>%3$s</option>',
                $term->slug,
                ( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
                $term->name
            );
        }
        echo '</select>';
    }
}

add_action( 'restrict_manage_posts', 'mbt_question_filter_by_taxonomies' , 10, 2);
function mbt_question_filter_by_taxonomies( $post_type, $which ) {
    if ( 'question' !== $post_type )
        return;
    $taxonomies = array( 'question_category' );
    foreach ( $taxonomies as $taxonomy_slug ) {
        $taxonomy_obj = get_taxonomy( $taxonomy_slug );
        $taxonomy_name = $taxonomy_obj->labels->name;
        $terms = get_terms( $taxonomy_slug );
        echo "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
        echo '<option value="">' .sprintf(__('所有%s','mobantu'), $taxonomy_name). '</option>';
        foreach ( $terms as $term ) {
            printf(
                '<option value="%1$s" %2$s>%3$s</option>',
                $term->slug,
                ( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
                $term->name
            );
        }
        echo '</select>';
    }
}