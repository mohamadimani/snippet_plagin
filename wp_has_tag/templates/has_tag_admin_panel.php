<style>

    div.row {
        padding: 5px 3px;
    }

    .last_edit_row {
        list-style: none;
        float: right;
        display: block;
        width: 100%;
    }

    .last_edit_row_item {
        display: block;
        width: 100% !important;
        height: 40px;
        background-color: #f0f0f1;
        float: right;
    }

    .postbox {
        height: auto !important;
        float: right !important;
        width: 100% !important;
    }


    div.info {
        display: flex;
    }

    span.key, .post_title {
        padding: 5px;
    }

</style>

<div id="dashboard-widgets-wrap" style="margin: 0">
    <div id="dashboard-widgets" class="metabox-holder">
        <!--box 1-->
        <div id="postbox-container-1" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="dashboard_right_now" class="postbox">
                    <div class="postbox-header">
                        <h2 class="hndle ui-sortable-handle"><b>لیست پست های بدون برچسب </b>
                            <a href="<?= esc_url(add_query_arg(array(
                                "export_has_meta_list" => 'yes',
                                "post_type" => $_POST['post_type']
                            ))); ?>"><b>خروجی : csv</b></a>
                        </h2>
                    </div>
                    <div class="inside">
                        <div class="main">
                            <form action="" method="post">
                                <b>
                                    <label for="post_types">
                                        <span> لیست پست تایپ ها :</span>
                                    </label>
                                    <select name="post_type" id="post_types">
                                        <option value="">انتخاب کنید ...</option>
                                        <?php
                                        $get_post_types = [
                                            ['id' => 'accessories', 'name' => 'کالای فرهنگی'],
                                            ['id' => 'advertise', 'name' => 'ریپورتاژ و آگهی'],
                                            ['id' => 'book', 'name' => ' کتاب ها'],
                                            ['id' => 'cast', 'name' => 'پادکست ها'],
                                            ['id' => 'event', 'name' => 'رویداد'],
                                            ['id' => 'magazine', 'name' => 'مجلات'],
                                            ['id' => 'news', 'name' => 'اخبار'],
                                            ['id' => 'page', 'name' => 'برگه ها'],
                                            ['id' => 'partner', 'name' => 'همکاران'],
                                            ['id' => 'post', 'name' => 'نوشته'],
                                        ];

                                        foreach ($get_post_types as $post_type) {
                                            $select = " ";
                                            if (isset($_POST['post_type']) and $_POST['post_type'] == $post_type['id']) {
                                                $select = "selected";
                                            }
                                            ?>
                                            <option <?= $select ?>
                                                    value="<?= $post_type['id'] ?>"><?= $post_type['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <input type="submit" value="ثبت">
                                </b>
                            </form>
                            <div class="row">
                                <ul class="last_edit_row">
                                    <?php
                                    $a = 1;
                                    if (isset($get_posts) and is_array($get_posts)) {
                                        foreach ($get_posts as $key => $post) {
                                            if (in_array($post->ID, $has_tags)) {
                                                continue;
                                            }
                                            ?>
                                            <li class="last_edit_row_item">
                                                <div class="row">
                                                    <div class="info">
                                                        <span class="key"><?= $a ?> - </span>
                                                        <p class="post_title"><b> <a
                                                                        href="post.php?post=<?= $post->ID ?>&action=edit"><?= $post->post_title ?></a></b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php $a++;
                                        }
                                    } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

