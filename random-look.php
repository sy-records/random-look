<?php
/*
Plugin Name: Random Look
Plugin URI: https://github.com/sy-records/random-look
Description: 添加随便看看，顾名思义就是随机给出文章来看看，很小但很实用。
Version: 1.0.1
Author: 沈唁
Author URI: https://qq52o.me
License: Apache 2.0
*/

define('RANDOM_LOOk_BASEFOLDER', plugin_basename(dirname(__FILE__)));

register_activation_hook(__FILE__, 'random_look_set_options');
function random_look_set_options()
{
    $options = array(
        'random_type' => 0,
    );
    add_option('random_look_options', $options, '', 'yes');
}

function random_look_add_setting_page()
{
    add_options_page('随便看看设置', '随便看看设置', 'manage_options', __FILE__, 'random_look_setting_page');
}
add_action('admin_menu', 'random_look_add_setting_page');
function random_look_plugin_action_links($links, $file)
{
    if ($file == plugin_basename(dirname(__FILE__) . '/random-look.php')) {
        $links[] = '<a href="options-general.php?page=' . RANDOM_LOOk_BASEFOLDER . '/random-look.php">设置</a>';
        $links[] = '<a href="https://github.com/sy-records/random-look" target="_blank">GitHub</a>';
    }
    return $links;
}
add_filter('plugin_action_links', 'random_look_plugin_action_links', 10, 2);

$options = get_option("random_look_options");
switch ($options['random_type']){
    case 0 :
        add_action('init', 'random_look_type_0');
        break;
    case 1 :
        if( isset( $_GET['random'] ) ? true : false ){
            add_action( 'template_redirect', 'random_look_type_1' );
        }
        break;
}

function random_look_type_0() {
    if( isset( $_GET['random'] ) ? true : false ){
        global $wpdb;
        nocache_headers();//禁止浏览器缓存
        $posts = get_posts('post_type=post&orderby=rand&numberposts=1');
        foreach($posts as $post) {
            $link = get_permalink($post);
        }
        wp_redirect($link,307);//307 临时跳转
        exit;
    }
}

function random_look_type_1() {
    global $wpdb;
    $query = "SELECT ID FROM $wpdb->posts WHERE post_type = 'post' AND post_password = '' AND post_status = 'publish' ORDER BY RAND() LIMIT 1";
    if ( isset( $_GET['random_cat_id'] )) {
        $random_cat_id = (int) sanitize_text_field($_GET['random_cat_id']);
        $query = "SELECT DISTINCT ID FROM $wpdb->posts AS p INNER JOIN $wpdb->term_relationships AS tr ON (p.ID = tr.object_id AND tr.term_taxonomy_id = $random_cat_id) INNER JOIN $wpdb->term_taxonomy AS tt ON(tr.term_taxonomy_id = tt.term_taxonomy_id AND taxonomy = 'category') WHERE post_type = 'post' AND post_password = '' AND post_status = 'publish' ORDER BY RAND() LIMIT 1";
    }
    if ( isset( $_GET['random_post_type'] ) ) {
        $post_type = preg_replace( '|[^a-z]|i', '', sanitize_text_field($_GET['random_post_type']));
        $query = "SELECT ID FROM $wpdb->posts WHERE post_type = '$post_type' AND post_password = '' AND post_status = 'publish' ORDER BY RAND() LIMIT 1";
    }
    $random_id = $wpdb->get_var( $query );
    wp_redirect( get_permalink( $random_id ) );
    exit;
}

function random_look_setting_page()
{
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient privileges!');
    }
    $options = array();
    if (!empty($_POST) and $_POST['type'] == 'random_look_set') {
        $options['random_type'] = isset($_POST['random_type']) ? sanitize_text_field($_POST['random_type']) : '';
    }

    if ($options !== array()) {
        update_option('random_look_options', $options);
        echo '<div class="updated"><p><strong>设置已保存！</strong></p></div>';
    }

$random_look_options = get_option('random_look_options', true);
$random_look_type = esc_attr($random_look_options['random_type']);

?>
<div class="wrap" style="margin: 10px;">
    <h1>随便看看设置</h1>
    <p>如果觉得此插件对你有所帮助，不妨到 <a href="https://github.com/sy-records/random-look" target="_blank">GitHub</a> 上点个<code>Star</code>，<code>Watch</code>关注更新；</p>
    <hr/>
    <form name="form" method="post" action="<?php echo wp_nonce_url('./options-general.php?page=' . RANDOM_LOOk_BASEFOLDER . '/random-look.php'); ?>">
        <table class="form-table">
            <tr>
                <th>
                    <legend>随机方式</legend>
                </th>
                <td>
                    <select name="random_type">
                        <option <?php if ($random_look_type == 0) {echo 'selected="selected"';} ?> value="0">直接随机</option>
                        <option <?php if ($random_look_type == 1) {echo 'selected="selected"';} ?> value="1">指定分类/文章类型</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <legend>保存/更新选项</legend>
                </th>
                <td><input type="submit" name="submit" class="button button-primary" value="保存更改"/></td>
            </tr>
        </table>
        <input type="hidden" name="type" value="random_look_set">
    </form>
</div>
<?php
}
?>