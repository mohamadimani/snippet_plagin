<?php
if (isset($_GET['export_has_meta_list']) and !empty($_GET['export_has_meta_list'])) {
    include("has_tag_exporter.php");
    has_meta_export();
    exit();
}

//    show post meta log  manage page in admin panel menu
function has_tag_panel_page()
{
    global $wpdb, $table_prefix;
    $term_relationships = $table_prefix . 'term_relationships';
    $wp_term_taxonomy = $table_prefix . 'term_taxonomy';
    $wp_posts = $table_prefix . 'posts';
    $wp_terms = $table_prefix . 'terms';
    $date = date('Y-m-d', time() - 86400);
    $two_last_edited_posts = '';
    $where = '';
    $edited_posts = '';

    if (isset($_POST['post_type']) and !empty(trim($_POST['post_type']))) {
        $post_type = esc_sql($_POST['post_type']);


        $get_posts_has_tag = $wpdb->get_results($wpdb->prepare(" SELECT rel.* ,  tx.*
                                                                        FROM    {$term_relationships} rel   
                                                                        left join {$wp_term_taxonomy} tx on tx.`term_taxonomy_id`= rel.term_taxonomy_id 
                                                                        where   tx.taxonomy='post_tag'  
                                                                        group by rel.object_id"));
        $has_tags = [];
        foreach ($get_posts_has_tag as $post) {
            $has_tags[] = $post->object_id;
        }
        $get_posts = $wpdb->get_results($wpdb->prepare(" SELECT ID, post_title , post_type FROM  {$wp_posts}   where  post_type='{$post_type}' order by ID desc  "));
    }

//    $get_post_types = $wpdb->get_results($wpdb->prepare(" SELECT tx.* , tr.* FROM {$wp_term_taxonomy} tx left join {$wp_terms} tr on  tx.term_id =tr.`term_id`  where tx.taxonomy='category' ORDER BY tx.`term_taxonomy_id`  DESC")); // select posts category
    include HAS_TAG_TEM . "has_tag_admin_panel.php";
//    require_once   PooyecoPlugin_DIR . '/templates/has_tag_admin_panel.php';

}


