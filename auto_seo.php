<?php
/*
Plugin Name: Auto SEO
Plugin URI: http://www.pgiauto.com/
Description: Speeds on site SEO time with a single, simple interface to control all the posts/pages.
Version: 1.3.5
Author: Phillip Gooch
Author URI: mailto:phillip@pgiauto.com
License: GNU General Public License v2
*/

#### Add menues
add_action('admin_menu','auto_seo_menu');
add_action('admin_bar_menu', 'auto_seo_admin_bar_menu',151);
function auto_seo_menu(){
	add_menu_page('Auto SEO','Auto SEO',1,'auto_seo','auto_seo','../wp-content/plugins/auto-seo/seo_icon.png',152);
}
function auto_seo_admin_bar_menu($admin_bar){
	$admin_bar->add_menu( array(
	    'id'    => 'auto_seo',
	    'title' => '<span class="ab-label">Auto SEO</span>',
	    'href'  => get_admin_url().'admin.php?page=auto_seo',
	    'meta'  => array(
	        'title' => __('Auto SEO Settings'),
	    ),
	) );
}
### Some themes don't want to play nice, were going to use some caching to force the issue
function auto_seo_obstrart($title,$sep=''){
	ob_start();
}
Add_filter('get_header','auto_seo_obstrart');
#Add_filter('get_header','auto_seo_obstrart');
function auto_seo_obflush($title,$sep=''){
	$head = ob_get_clean();
	$head = preg_replace('~(?<!(<!-- Auto SEO -->))<title.*>.+</title>~i','',$head);
	$head = preg_replace('~(?<!(<!-- Auto SEO -->))<meta name="description".* />~i','',$head);
	$head = preg_replace('~(?<!(<!-- Auto SEO -->))<meta name="keywords".*/>~i','',$head);
	$head = preg_replace('~(?<!(<!-- Auto SEO -->))<meta name="robots".*/>~i','',$head);
	echo $head;
}
Add_filter('loop_start','auto_seo_obflush');

#### Add in the new meta data
function auto_seo_addin(){
 	$settings = get_option('auto_seo_settings','');
	if($settings!=''){
		$settings = unserialize($settings);
	   	global $wp_query;
	   	if(isset($wp_query->queried_object->ID)){
	   		$id = trim($wp_query->queried_object->ID);
	   	}else{
	   		// Only happens if on homepage and homepage is blog
	   		$id = 0;
	   	}
		$settings = get_option('auto_seo_settings');
		$settings = unserialize($settings);
		#### Determine Title (and city, which is used everywhere)
		$first_title_slugs = explode("\n", $settings['first_title_slug']);
		$first_title_slug_count = count($first_title_slugs)-1;
		$second_title_slugs = explode("\n", $settings['second_title_slug']);
		$second_title_slug_count = count($second_title_slugs)-1;
		$cities = explode("\n", $settings['cities']);
		$city_count = count($cities)-1;
		$selected_first_title_slug = $id;
		$selected_second_title_slug = $id;
		$selected_city = $id;
		while($selected_first_title_slug>$first_title_slug_count){$selected_first_title_slug=$selected_first_title_slug-$first_title_slug_count;}
		while($selected_second_title_slug>$second_title_slug_count){$selected_second_title_slug=$selected_second_title_slug-$second_title_slug_count;}
		while($selected_city>$city_count){$selected_city=$selected_city-$city_count;}
		if($id == get_option('page_on_front')){
			$selected_city = 0;
			$selected_first_title_slug = 0;
			$selected_second_title_slug = 0;
		}
			$title = $first_title_slugs[$selected_first_title_slug].' '.($settings['title_seperator']==''?'':$settings['title_seperator'].' ').$second_title_slugs[$selected_second_title_slug].' '.($settings['title_seperator']==''?'':$settings['title_seperator'].' ').$cities[$selected_city];
		#### Determine Description
		$description = stripslashes(str_ireplace('[city]',$cities[$selected_city],$settings['description']));
		#### Determine Keywords
		$keywords = explode(',',$settings['keywords']);
		$keyword_count = count($keywords);
		$keywords_selected = 0;
		$keyword_selection_point = $id*3;
		$keyword_string = '';
		while($keywords_selected <= 10){
			$keyword_selection_point++;
			if(!isset($keywords[$keyword_selection_point])){
				$keyword_selection_point = $keyword_selection_point-$keyword_count;
			}
			if(isset($keywords[$keyword_selection_point])){
				$keyword_string .= trim($keywords[$keyword_selection_point]).', ';
			}
			$keywords_selected++;
		}
		$keywords = $keyword_string.$cities[$selected_city];
		#### output the new meta infos
		echo '<!-- Auto SEO --><title>'.$title.'</title>';
		echo "\n";
		echo '<!-- Auto SEO --><meta name="description" content="'.$description.'">';
		echo "\n";
		echo '<!-- Auto SEO --><meta name="keywords" content="'.$keywords.'">';
		echo "\n";
		echo '<!-- Auto SEO --><meta name="robots" content="index,follow,noodp,noydir" />';
		echo "\n";
	}
}
Add_filter('wp_head','auto_seo_addin');

#### This function controls the admin page
function auto_seo(){
$updated = false;
$settings = get_option('auto_seo_settings');
$settings = unserialize($settings);
if(isset($_POST['auto_seo_update'])){
	unset($_POST['auto_seo_update']);
	foreach($_POST as $k => $v){
		$settings[$k] = trim($v);
	}
	update_option('auto_seo_settings',serialize($settings));
	$updated = true;
}
?>
<div class="wrap">
	<div id="icon-tools" class="icon32"><br></div><h2>Auto SEO Settings</h2>
	<?= ($updated?'<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Settings saved.</strong></p></div>':'') ?>
	<form action="" method="POST">
		<input type="hidden" name="auto_seo_update" value="yes" />
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="cities">Cities<br/><span class="description">Used in multiple locations throughout, one per line.</span></label></th></th>
					<td><textarea name="cities" id="cities" class="regular-text" rows="3" style="width:500px;"><?= (stripslashes($settings['cities'])) ?></textarea></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="first_title_slug">First Title Slug<br/><span class="description">Used in page title, one per line. Followed by a Second Title Slug.</span></label></th></th>
					<td><textarea name="first_title_slug" id="first_title_slug" class="regular-text" rows="3" style="width:500px;"><?= (stripslashes($settings['first_title_slug'])) ?></textarea></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="second_title_slug">Second Title Slug<br/><span class="description">Used in page title, one per line. Followed by a City</span></label></th></th>
					<td><textarea name="second_title_slug" id="second_title_slug" class="regular-text" rows="3" style="width:500px;"><?= (stripslashes($settings['second_title_slug'])) ?></textarea></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="title_seperator">Title Seperator</label></th></th>
					<td><input type="text" name="title_seperator" id="title_seperator" value="<?= ($settings['title_seperator']==''?'|':$settings['title_seperator']) ?>" style="width: 25px;text-align:center;" /> <span class="description">Will be used between Title Slugs and the City.</span></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="keywords">Keywords<br/><span class="description">10 will be randomly selected, with City appended. Comma separated.</span></label></th></th>
					<td><textarea name="keywords" id="keywords" class="regular-text" rows="3" style="width:500px;"><?= (stripslashes($settings['keywords'])) ?></textarea></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="description">Description<br/><span class="description">Will be used on every page, use the shortcode <code>[city]</code> to automatically insert a City.</span></label></th></th>
					<td><textarea name="description" id="description" class="regular-text" rows="3" style="width:500px;"><?= (stripslashes($settings['description'])) ?></textarea></td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="Update Auto SEO Settings">
		</p>
	</form>
</div>
<?php }