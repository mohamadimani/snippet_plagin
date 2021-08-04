<?php
function has_meta_export()
{
    global $wpdb, $table_prefix;
    $term_relationships = $table_prefix . 'term_relationships';
    $wp_term_taxonomy = $table_prefix . 'term_taxonomy';
    $wp_posts = $table_prefix . 'posts';

    $date = date('Y-m-d', time());
    $date_p = g_date_to_p($date, '-');

    if (isset($_GET['post_type']) and !empty(trim($_GET['post_type']))) {
        $post_type = esc_sql($_GET['post_type']);

        $get_posts_has_tag = $wpdb->get_results($wpdb->prepare(" SELECT rel.* ,  tx.*
                                                                        FROM    {$term_relationships} rel   
                                                                        left join {$wp_term_taxonomy} tx on tx.`term_taxonomy_id`= rel.term_taxonomy_id 
                                                                        where   tx.taxonomy='post_tag'  
                                                                        group by rel.object_id"));

        $has_tags = [];
        foreach ($get_posts_has_tag as $post) {
            $has_tags[] = $post->object_id;
        }

        $get_posts = $wpdb->get_results($wpdb->prepare(" SELECT ID, post_title , post_type FROM  {$wp_posts}   where  post_type='{$post_type}' order by ID desc  "), ARRAY_A);


        $file_name = $date_p . '_' . $_GET['export_has_meta_list'] . "_users_list.csv";
        $export_file = fopen('php://output', 'w');
        header('Content-Disposition: attachment; filename="' . $file_name . '";');
        header('Content-Encoding: UTF-8');
        header("content-type:text/csv;charset=UTF-8");
        echo "\xEF\xBB\xBF";
        header('Cache-Control:no-cache, no-store,must-revalidate');
        header('Expires: 0');
        header('Pragma: no-cache');
        $text = [];
        foreach ($get_posts as $key => $posts) {
            if (in_array($posts->ID, $has_tags)) {
                continue;
            }
            if ($key == 0) {
                fputcsv($export_file, [$date_p, ' تاریخ گزارش : ', ' لیست پست های بدون برچسب  '], ',', '"', "\\");
                fputcsv($export_file, [" ردیف ", ' پست :   ', '   لینک : '], ',', '"', "\\");
            }
            $post_id = $posts['ID'];
            $posts['ID'] = $key + 1;
            $posts['post_type'] = $posts['post_title'];
            $posts['post_title'] =$_SERVER['HTTP_HOST']. "/wp-admin/post.php?post=$post_id&amp;action=edit";
            $text[] = $posts;

        }
        foreach ($text as $key => $text2) {
            fputcsv($export_file, $text2, ",", '"', "\\");
        }
        fclose($export_file);
    }
}

//  convert gregorian date to persian
function g_date_to_p($date = '', $ex_sign = '/')
{
    if (!empty(trim($date))) {
        if (!function_exists('gregorian_to_jalali')) {
            include HAS_TAG_INC . 'jdf.php';
//            require_once   PooyecoPlugin_DIR . '/inc/jdf.php';
        }
        $g_date = explode($ex_sign, $date);
        $date_p = gregorian_to_jalali($g_date[0], $g_date[1], $g_date[2], '');
        return $date_p[0] . $ex_sign . $date_p[1] . $ex_sign . $date_p[2];
    } else {
        return "";
    }
}

?>
