<?php
/**
Plugin Name: Mybb Last Inforamtion
Description: This Plugin Show Mybb Info In Wordpress . Like Last post,Last User,Last Thread,Top Download
Version: 2.0.2
Author: <a href="http://mypgr.ir/">Mostafa Shiraali</a>
Author URI: http://mypgr.ir/
License: A "Slug" license name e.g. GPL2
Text Domain: mybblastinfo
Domain Path: /languages
 */
 
MybbLastInfo::init();
class MybbLastInfo
{
		public static function init()
	{
	add_action('admin_init', array(__CLASS__,'registersetting') );
	add_action('init',array(__CLASS__,'lang_init'));
	add_action('admin_init',array(__CLASS__,'lang_init'));
	add_action('admin_menu',array(__CLASS__,'menu'));
	add_action('widgets_init',array(__CLASS__,'widget_init'));
	add_action( 'wp_enqueue_scripts',array(__CLASS__,'script') );
	register_activation_hook( __FILE__,array(__CLASS__,'active') );
	register_deactivation_hook( __FILE__,array(__CLASS__,'deactivate'));
	
	add_shortcode('show_forumstat',array(__CLASS__,'show_forumstat') );
	add_shortcode('show_lastpost',array(__CLASS__,'show_lastpost') );
	add_shortcode('show_lastuser',array(__CLASS__,'show_lastuser') );
	add_shortcode('show_mostviewed',array(__CLASS__,'show_mostviewed') );
	add_shortcode('show_hottopics',array(__CLASS__,'show_hottopics') );
	add_shortcode('show_topposter',array(__CLASS__,'show_topposter') );
	add_shortcode('show_toprep',array(__CLASS__,'show_toprep') );
	add_shortcode('show_topfile',array(__CLASS__,'show_topfile') );
	add_shortcode('show_topref',array(__CLASS__,'show_topref') );
	
	add_filter( 'widget_text',array(__CLASS__,'handle_widgettext'));
	
	add_action('wp_footer',array(__CLASS__,'mybblastinfo_js')); 
	add_action('wp_ajax_refreshinfo',array(__CLASS__,'refreshinfo_callback'));
	add_action('wp_ajax_nopriv_refreshinfo',array(__CLASS__,'refreshinfo_callback'));
	}
	
	public static function handle_widgettext($text)
	{
		switch ($text) {
    case "[show_forumstat]":
       return MybbLastInfo::show_forumstat();
        break;
    case "[show_lastpost]":
        return MybbLastInfo::show_lastpost();
        break;
    case "[show_lastuser]":
        return MybbLastInfo::show_lastuser();
        break;
	    case "[show_mostviewed]":
        return MybbLastInfo::show_mostviewed();
        break;
		    case "[show_hottopics]":
        return MybbLastInfo::show_hottopics();
        break;
		    case "[show_topposter]":
        return MybbLastInfo::show_topposter();
        break;
		    case "[show_toprep]":
        return MybbLastInfo::show_toprep();
        break;
		    case "[show_topfile]":
        return MybbLastInfo::show_topfile();
        break;
		    case "[show_topref]":
        return MybbLastInfo::show_topref();
        break;
    default:
        return $text;
		}
	}
	public static function mybblastinfo_js()
	{
	?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		jQuery(".refreshmli").live('click',function(event){
		var targetelm=jQuery(event.currentTarget).attr('id');
		var parent_elm=jQuery(event.currentTarget).parent().parent();
		jQuery(event.currentTarget).css("opacity",0.3);
		var data = {
			'action': 'refreshinfo',
			'itemkey': targetelm
		};

		jQuery.post(ajaxurl, data, function(response) {
		parent_elm.html(response);
		jQuery(event.currentTarget).css("opacity",1);			
		});
		
		});

	});
	</script> <?php
	}
  public static function refreshinfo_callback()
  {		  
		  $itemkey=$_POST['itemkey'];
		  if($itemkey=="forumstat"){echo strip_tags(MybbLastInfo::show_forumstat(),"<li><input><img>");exit();}
		  if($itemkey=="lastpost"){echo strip_tags(MybbLastInfo::show_lastpost(),"<li><input><img><a>");exit();}
		  if($itemkey=="lastuser"){echo strip_tags(MybbLastInfo::show_lastuser(),"<li><input><img><a>");exit();}
		  if($itemkey=="mostview"){echo strip_tags(MybbLastInfo::show_mostviewed(),"<li><input><img><a>");exit();}
		  if($itemkey=="hottopics"){echo strip_tags(MybbLastInfo::show_hottopics(),"<li><input><img><a>");exit();}
		  if($itemkey=="topposter"){echo strip_tags(MybbLastInfo::show_topposter(),"<li><input><img><a>");exit();}
		  if($itemkey=="toprep"){echo strip_tags(MybbLastInfo::show_toprep(),"<li><input><img><a>");exit();}
		  if($itemkey=="topfile"){echo strip_tags(MybbLastInfo::show_topfile(),"<li><input><img><a>");exit();}
		  if($itemkey=="topref"){echo strip_tags(MybbLastInfo::show_topref(),"<li><input><img><a>");exit();}
	  
  }
  public static function active()
 {
 add_option('mli_mybb_host',"localhost");
 add_option('mli_mybb_db',"");
 add_option('mli_mybb_db_username',"");
 add_option('mli_mybb_db_password',"");
 add_option('mli_mybb_tableprefix',"mybb_");
 add_option('mli_lp_num',5);
 add_option('mli_lu_num',5);
 add_option('mli_mv_num',5);
 add_option('mli_ht_num',5);
 add_option('mli_tr_num',5);
 add_option('mli_tf_num',5);
 add_option('mli_tp_num',5);
 add_option('mli_tref_num',5);
 add_option('mli_lastpost',"");
 add_option('mli_lastuser',"");
 add_option('mli_mostview',"");
 add_option('mli_hottopic',"");
 add_option('mli_topreputation',"");
 add_option('mli_topfiles',"");
 add_option('mli_topposter',"");
 add_option('mli_lastpostchar',45);
 add_option('mli_filetitle',25);
 add_option('mli_fstat',"");
 add_option('mli_reffer',"");
 }
 public static function registersetting()
 {
 register_setting('ctboard_mli_opt','mli_mybb_host');
 register_setting('ctboard_mli_opt','mli_mybb_db');
 register_setting('ctboard_mli_opt','mli_mybb_db_username');
 register_setting('ctboard_mli_opt','mli_mybb_db_password');
 register_setting('ctboard_mli_opt','mli_mybb_tableprefix');
 register_setting('ctboard_mli_opt','mli_lp_num');
 register_setting('ctboard_mli_opt','mli_lu_num');
 register_setting('ctboard_mli_opt','mli_mv_num');
 register_setting('ctboard_mli_opt','mli_ht_num');
 register_setting('ctboard_mli_opt','mli_tr_num');
 register_setting('ctboard_mli_opt','mli_tf_num');
 register_setting('ctboard_mli_opt','mli_tp_num');
 register_setting('ctboard_mli_opt','mli_tref_num');
 register_setting('ctboard_mli_opt','mli_lastpost');
 register_setting('ctboard_mli_opt','mli_lastuser');
 register_setting('ctboard_mli_opt','mli_mostview');
 register_setting('ctboard_mli_opt','mli_hottopic');
 register_setting('ctboard_mli_opt','mli_topreputation');
 register_setting('ctboard_mli_opt','mli_topfiles');
 register_setting('ctboard_mli_opt','mli_topposter');
 register_setting('ctboard_mli_opt','mli_lastpostchar');
 register_setting('ctboard_mli_opt','mli_filetitle');
 register_setting('ctboard_mli_opt','mli_fstat');
 register_setting('ctboard_mli_opt','mli_reffer');
 }
  public static function deactivate()
 {
 delete_option('mli_mybb_host');
 delete_option('mli_mybb_db');
 delete_option('mli_mybb_db_username');
 delete_option('mli_mybb_db_password');
 delete_option('mli_mybb_tableprefix');
 delete_option('mli_lp_num');
 delete_option('mli_lu_num');
 delete_option('mli_mv_num');
 delete_option('mli_ht_num');
 delete_option('mli_tr_num');
 delete_option('mli_tf_num');
 delete_option('mli_tp_num');
 delete_option('mli_tref_num');
 delete_option('mli_lastpost');
 delete_option('mli_lastuser');
 delete_option('mli_mostview');
 delete_option('mli_hottopic');
 delete_option('mli_topreputation');
 delete_option('mli_topfiles');
 delete_option('mli_topposter');
 delete_option('mli_lastpostchar');
 delete_option('mli_filetitle');
 delete_option('mli_fstat');
 delete_option('mli_reffer');
 }
 public static function lang_init()
 {
   load_plugin_textdomain( 'mybblastinfo', false,dirname( plugin_basename( __FILE__ ) ) .'/languages/' );
 }
 
	public static function menu()
	{
	add_options_page(__("Mybb Last Information","mybblastinfo"), __("Mybb Info","mybblastinfo"), 10, __FILE__,array(__CLASS__,"display_options"));
	}
	public static function display_options()
	{
	?>
	<div class="wrap">
	<h2><?php _e("Mybb Last Information Options","mybblastinfo")?></h2>        
	<form method="post" action="options.php">
	<?php settings_fields('ctboard_mli_opt'); ?>
	<table class="form-table">
	    <tr valign="top">
            <th scope="row"><label><?php _e("Host of Mybb","mybblastinfo");?></label></th>
			<td><input type="text" name="mli_mybb_host" value="<?php echo get_option('mli_mybb_host'); ?>" /> </td>
        </tr>
			    <tr valign="top">
            <th scope="row"><label><?php _e("Database name","mybblastinfo");?></label></th>
			<td><input type="text" name="mli_mybb_db" value="<?php echo get_option('mli_mybb_db'); ?>" /> </td>
        </tr>
			    <tr valign="top">
            <th scope="row"><label><?php _e("Database Username","mybblastinfo");?></label></th>
			<td><input type="text" name="mli_mybb_db_username" value="<?php echo get_option('mli_mybb_db_username'); ?>" /> </td>
        </tr>
			    <tr valign="top">
            <th scope="row"><label><?php _e("Database Password","mybblastinfo");?></label></th>
			<td><input type="password" name="mli_mybb_db_password" value="<?php echo get_option('mli_mybb_db_password'); ?>" /> </td>
        </tr>
			    <tr valign="top">
            <th scope="row"><label><?php _e("Mybb Table Prefix","mybblastinfo");?></label></th>
			<td><input type="text" name="mli_mybb_tableprefix" value="<?php echo get_option('mli_mybb_tableprefix'); ?>" /> </td>
        </tr>
		</tr>
		<tr valign="top">
            <th scope="row"><label><?php _e("Last Post Items Number","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Insert Number of Last Post Items","mybblastinfo");?></span></td>
			<td><input type="text" name="mli_lp_num" value="<?php echo get_option('mli_lp_num'); ?>" /> </td>
        </tr>	
		<tr valign="top">
            <th scope="row"><label><?php _e("Last Users Items Number","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Insert Number of Last Users Items","mybblastinfo");?></span></td>
			<td><input type="text" name="mli_lu_num" value="<?php echo get_option('mli_lu_num'); ?>" /> </td>
        </tr>	
		<tr valign="top">
            <th scope="row"><label><?php _e("Most View Items Number","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Insert Number of Most View Items","mybblastinfo");?></span></td>
			<td><input type="text" name="mli_mv_num" value="<?php echo get_option('mli_mv_num'); ?>" /> </td>
        </tr>	
		<tr valign="top">
            <th scope="row"><label><?php _e("Hot Topics Items Number","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Insert Number of Hot Topics Items","mybblastinfo");?></span></td>
			<td><input type="text" name="mli_ht_num" value="<?php echo get_option('mli_ht_num'); ?>" /> </td>
        </tr>	
		<tr valign="top">
            <th scope="row"><label><?php _e("Top Reputation Items Number","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Insert Number of Top Reputation Items","mybblastinfo");?></span></td>
			<td><input type="text" name="mli_tr_num" value="<?php echo get_option('mli_tr_num'); ?>" /> </td>
        </tr>	
		<tr valign="top">
            <th scope="row"><label><?php _e("Top Files Items Number","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Insert Number of Top Files Items","mybblastinfo");?></span></td>
			<td><input type="text" name="mli_tf_num" value="<?php echo get_option('mli_tf_num'); ?>" /> </td>
        </tr>	
		<tr valign="top">
            <th scope="row"><label><?php _e("Top Writer Items Number","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Insert Number of Top Writer Items","mybblastinfo");?></span></td>
			<td><input type="text" name="mli_tp_num" value="<?php echo get_option('mli_tp_num'); ?>" /> </td>
        </tr>	
		<tr valign="top">
            <th scope="row"><label><?php _e("Top Reffer Items Number","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Insert Number of Top Reffer Items","mybblastinfo");?></span></td>
			<td><input type="text" name="mli_tref_num" value="<?php echo get_option('mli_tref_num'); ?>" /> </td>
        </tr>		
		</tr>
		 <tr valign="top">
            <th scope="row"><label><?php _e("Post Title Characters","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Insert Number of Post Title Characters","mybblastinfo");?></span></td>
			<td><input type="text" name="mli_lastpostchar" value="<?php echo get_option('mli_lastpostchar'); ?>" /> </td>
        </tr>
		<tr valign="top">
            <th scope="row"><label><?php _e("File Title Characters","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Insert Number of File Title Characters","mybblastinfo");?></span></td>
			<td><input type="text" name="mli_filetitle" value="<?php echo get_option('mli_filetitle'); ?>" /> </td>
        </tr>
		<tr valign="top">
            <th scope="row"><label><?php _e("Forum Statistics","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Do You Want Show Forum Statistics?","mybblastinfo");?></span></td>
			<td><input type="checkbox" name="mli_fstat" id="mli_fstat" <?php checked('mli_fstat', get_option('mli_fstat'));?> value='mli_fstat'/></td>
        </tr>
		<tr valign="top">
            <th scope="row"><label><?php _e("Last Posts","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Do You Want Show Last Mybb Posts?","mybblastinfo");?></span></td>
			<td><input type="checkbox" name="mli_lastpost" id="mli_lastpost" <?php checked('mli_lastpost', get_option('mli_lastpost'));?> value='mli_lastpost'/></td>
        </tr>
		<tr valign="top">
            <th scope="row"><label><?php _e("Last Users","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Do You Want Show Last Mybb Users?","mybblastinfo");?> </span></td>
			<td><input type="checkbox" name="mli_lastuser" id="mli_lastuser" <?php checked('mli_lastuser', get_option('mli_lastuser'));?> value='mli_lastuser'/></td>
        </tr>
		<tr valign="top">
            <th scope="row"><label><?php _e("Most Viewed Posts","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Do You Want Show Most Viewed Posts?","mybblastinfo");?> </span></td>
			<td><input type="checkbox" name="mli_mostview" id="mli_mostview" <?php checked('mli_mostview', get_option('mli_mostview'));?> value='mli_mostview'/></td>

        </tr>
		<tr valign="top">
            <th scope="row"><label><?php _e("Most Popular Topics","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Do You Want Show Most Popular Topic?","mybblastinfo");?> </span></td>
			<td><input type="checkbox" name="mli_hottopic" id="mli_hottopic" <?php checked('mli_hottopic', get_option('mli_hottopic'));?> value='mli_hottopic'/></td>
        </tr>	
		<tr valign="top">
            <th scope="row"><label><?php _e("Top Reputation","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Do You Want Show Top Reputation?","mybblastinfo");?> </span></td>
			<td><input type="checkbox" name="mli_topreputation" id="mli_topreputation" <?php checked('mli_topreputation', get_option('mli_topreputation'));?> value='mli_topreputation'/></td>
        </tr>		
		<tr valign="top">
            <th scope="row"><label><?php _e("Top Files","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Do You Want Show Top Files?","mybblastinfo");?></span></td>
			<td><input type="checkbox" name="mli_topfiles" id="mli_topfiles" <?php checked('mli_topfiles', get_option('mli_topfiles'));?> value='mli_topfiles'/></td>
        </tr>		
		<tr valign="top">
            <th scope="row"><label><?php _e("Top Posters","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Do You Want Show Top Posters?","mybblastinfo");?></span></td>
			<td><input type="checkbox" name="mli_topposter" id="mli_topposter" <?php checked('mli_topposter', get_option('mli_topposter'));?> value='mli_topposter'/></td>
        </tr>
		<tr valign="top">
            <th scope="row"><label><?php _e("Top Reffer","mybblastinfo");?></label></th>
			<td><span class="description"><?php _e("Do You Want Show Top Reffer?","mybblastinfo");?></span></td>
			<td><input type="checkbox" name="mli_reffer" id="mli_reffer" <?php checked('mli_reffer', get_option('mli_reffer'));?> value='mli_reffer'/></td>
        </tr>
	</table>
<?php submit_button(); ?>
		</form><br/><br/>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="A6CRNP7LB2FFQ">
<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>
	</div>
	<?php
	}
	public static function query($query)
	{
	$hostname=get_option('mli_mybb_host');
	$db_user=get_option('mli_mybb_db_username');
	$db_pass=get_option('mli_mybb_db_password');
	$db_dbname=get_option('mli_mybb_db');
	$link = mysqli_connect($hostname,$db_user,$db_pass,$db_dbname);

	if (!$link)
	{
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    }
	mysqli_set_charset($link,"utf8"); 
	return mysqli_query($link, $query);
	}
	public static function fetch($fetch)
	{
		
	}
	public static function show_forumstat()
	{
	$table_prefix=get_option('mli_mybb_tableprefix');
	$fstat_users =MybbLastInfo::query("SELECT * FROM ".$table_prefix."users");
	$fstat_threads =MybbLastInfo::query("SELECT * FROM ".$table_prefix."threads");
	$fstat_posts =MybbLastInfo::query("SELECT * FROM ".$table_prefix."posts");
	$fstat_banned =MybbLastInfo::query("SELECT * FROM ".$table_prefix."banned");
	$fstat_groups =MybbLastInfo::query("SELECT * FROM ".$table_prefix."usergroups");
	$fstat_forums =MybbLastInfo::query("SELECT * FROM ".$table_prefix."forums");
	$fstat_polls =MybbLastInfo::query("SELECT * FROM ".$table_prefix."polls");
	$mybb_info ='<ul id="mli_items">';
	$mybb_info .="<li>".__("Members","mybblastinfo")." : ".mysqli_num_rows($fstat_users)."</li>";
	$mybb_info .="<li>".__("Threads","mybblastinfo")." : ".mysqli_num_rows($fstat_threads)."</li>";
	$mybb_info .="<li>".__("Posts","mybblastinfo")." : ".mysqli_num_rows($fstat_posts)."</li>";
	$mybb_info .="<li>".__("Banned Users","mybblastinfo")." : ".mysqli_num_rows($fstat_banned)."</li>";
	$mybb_info .="<li>".__("User Groups","mybblastinfo")." : ".mysqli_num_rows($fstat_groups)."</li>";
	$mybb_info .="<li>".__("Forums","mybblastinfo")." : ".mysqli_num_rows($fstat_forums)."</li>";
	$mybb_info .="<li>".__("Polls","mybblastinfo")." : ".mysqli_num_rows($fstat_polls)."</li>";
	$mybb_info .='<li><img src="'.plugins_url( 'core/refresh.png', __FILE__ ).'" class="refreshmli" id="forumstat"></li>';
	$mybb_info .='</ul>';
	return $mybb_info;
		
	}
	public static function show_lastpost()
	{
	$table_prefix=get_option('mli_mybb_tableprefix');
	$mli_lp_num=get_option('mli_lp_num');
	$query_forum=MybbLastInfo::query("SELECT * FROM  ".$table_prefix."settings WHERE name='bburl'");
	$fetch_forum=mysqli_fetch_array($query_forum);
	$forum_url=$fetch_forum['value'];
	$query_posts=MybbLastInfo::query("SELECT t1.*
	FROM ".$table_prefix."posts t1
	JOIN (
	SELECT pid
	FROM ".$table_prefix."posts
	WHERE visible='1' ORDER BY pid DESC
	)t2 ON t1.pid = t2.pid GROUP BY tid ORDER BY pid DESC");	
	$mybb_info ='<ul id="mli_items">';
	$post_count=0;
	$lastpost_chars_number=get_option('mli_lastpostchar');
	while(($fetch=mysqli_fetch_array($query_posts)) && $post_count<$mli_lp_num)
	{
	$mybb_info .="<li><a href=".$forum_url."/showthread.php?tid=".$fetch['tid']."&pid=".$fetch['pid']." target=\"_blank\">".mb_substr($fetch['subject'],0,$lastpost_chars_number,'UTF-8')."</a></li>";
	
	$post_count++;
	}
	$mybb_info .='<li><img src="'.plugins_url( 'core/refresh.png', __FILE__ ).'" class="refreshmli" id="lastpost"></li>';
	$mybb_info.='</ul>';
	
	return $mybb_info;
	}
	public static function show_lastuser()
	{
	$query_forum=MybbLastInfo::query("SELECT * FROM  ".$table_prefix."settings WHERE name='bburl'");
	$fetch_forum=mysqli_fetch_array($query_forum);
	$forum_url=$fetch_forum['value'];
	$mli_lu_num=get_option('mli_lu_num');
	$table_prefix=get_option('mli_mybb_tableprefix');
	$query_users=MybbLastInfo::query("SELECT * FROM  ".$table_prefix."users ORDER BY uid DESC LIMIT 0,{$mli_lu_num}");
	$mybb_info='<ul id="mli_items">';
	while($fetch=mysqli_fetch_array($query_users))
	{
	$mybb_info .="<li><a href=".$forum_url."/member.php?action=profile&uid=".$fetch['uid']." target=\"_blank\">".$fetch['username']."</a></li>";
	}
	$mybb_info .='<li><img src="'.plugins_url( 'core/refresh.png', __FILE__ ).'" class="refreshmli" id="lastuser"></li>';
	$mybb_info.='</ul>';
	return $mybb_info;
	}
	public static function show_mostviewed()
	{
	
	$table_prefix=get_option('mli_mybb_tableprefix');
	$mli_mv_num=get_option('mli_mv_num');
	$query_forum=MybbLastInfo::query("SELECT * FROM  ".$table_prefix."settings WHERE name='bburl'");
	$fetch_forum=mysqli_fetch_array($query_forum);
	$forum_url=$fetch_forum['value'];
	$query_mostviewed =MybbLastInfo::query("SELECT * FROM ".$table_prefix."threads WHERE visible='1' ORDER BY views DESC LIMIT 0,{$mli_mv_num}");
	$mybb_info='<ul id="mli_items">';
	while($fetch=mysqli_fetch_array($query_mostviewed))
	{
	$mybb_info .="<li><a href=".$forum_url."/showthread.php?tid=".$fetch['tid']." target=\"_blank\">".mb_substr($fetch['subject'],0,$lastpost_chars_number,'UTF-8')."</a></li>";
		
	}
	$mybb_info .='<li><img src="'.plugins_url( 'core/refresh.png', __FILE__ ).'" class="refreshmli" id="mostview"></li>';
	$mybb_info.='</ul>';
	return $mybb_info;
	}
	public static function show_hottopics()
	{
	$table_prefix=get_option('mli_mybb_tableprefix');
	$mli_ht_num=get_option('mli_ht_num');
	$query_forum=MybbLastInfo::query("SELECT * FROM  ".$table_prefix."settings WHERE name='bburl'");
	$fetch_forum=mysqli_fetch_array($query_forum);
	$forum_url=$fetch_forum['value'];
	$query_hottopics = MybbLastInfo::query("SELECT * FROM ".$table_prefix."threads WHERE visible='1' ORDER BY replies DESC LIMIT 0,{$mli_ht_num}");
	$mybb_info='<ul id="mli_items">';
	while($fetch=mysqli_fetch_array($query_hottopics))
	{
	$mybb_info .="<li><a href=".$forum_url."/showthread.php?tid=".$fetch['tid']." target=\"_blank\">".mb_substr($fetch['subject'],0,$lastpost_chars_number,'UTF-8')."</a></li>";
	
	}
	$mybb_info .='<li><img src="'.plugins_url( 'core/refresh.png', __FILE__ ).'" class="refreshmli" id="hottopics"></li>';
	$mybb_info.='</ul>';
	return $mybb_info;
	}
	public static function show_topposter()
	{
	$table_prefix=get_option('mli_mybb_tableprefix');
	$mli_tp_num=get_option('mli_tp_num');
	$query_forum=MybbLastInfo::query("SELECT * FROM  ".$table_prefix."settings WHERE name='bburl'");
	$fetch_forum=mysqli_fetch_array($query_forum);
	$forum_url=$fetch_forum['value'];
	$query_top_poster = MybbLastInfo::query("SELECT * FROM ".$table_prefix."users ORDER BY postnum DESC LIMIT 0,{$mli_tp_num}");
	$mybb_info='<ul id="mli_items">';
	while($fetch=mysqli_fetch_array($query_top_poster))
	{
	$mybb_info .="<li><a href=".$forum_url."/member.php?action=profile&uid=".$fetch['uid']." target=\"_blank\">".$fetch['username']."</a></li>";
	}
	$mybb_info .='<li><img src="'.plugins_url( 'core/refresh.png', __FILE__ ).'" class="refreshmli" id="topposter"></li>';
	$mybb_info.='</ul>';
	return $mybb_info;
	}
	public static function show_toprep()
	{
	$table_prefix=get_option('mli_mybb_tableprefix');
	$mli_tr_num=get_option('mli_tr_num');
	$query_forum=MybbLastInfo::query("SELECT * FROM  ".$table_prefix."settings WHERE name='bburl'");
	$fetch_forum=mysqli_fetch_array($query_forum);
	$forum_url=$fetch_forum['value'];
	$query_top_reputation =MybbLastInfo::query("SELECT * FROM ".$table_prefix."users ORDER BY reputation DESC LIMIT 0,{$mli_tr_num}");
	$mybb_info='<ul id="mli_items">';
	while($fetch=mysqli_fetch_array($query_top_reputation))
	{
	$mybb_info .="<li><a href=".$forum_url."/member.php?action=profile&uid=".$fetch['uid']." target=\"_blank\">".$fetch['username']."</a></li>";
	}
	$mybb_info .='<li><img src="'.plugins_url( 'core/refresh.png', __FILE__ ).'" class="refreshmli" id="toprep"></li>';
	$mybb_info.='</ul>';
	return $mybb_info;
	}
	public static function show_topfile()
	{
	$table_prefix=get_option('mli_mybb_tableprefix');
	$mli_tf_num=get_option('mli_tf_num');
	$file_chars_number=get_option('mli_filetitle');
	$query_forum=MybbLastInfo::query("SELECT * FROM  ".$table_prefix."settings WHERE name='bburl'");
	$fetch_forum=mysqli_fetch_array($query_forum);
	$forum_url=$fetch_forum['value'];
	$query_top_file = MybbLastInfo::query("SELECT * FROM ".$table_prefix."attachments ORDER BY downloads DESC LIMIT 0,{$mli_tf_num}");
	$mybb_info='<ul id="mli_items">';
	while($fetch=mysqli_fetch_array($query_top_file))
	{
	$mybb_info .="<li><a href=".$forum_url."/showthread.php?pid==".$fetch['pid']." target=\"_blank\">".mb_substr($fetch['filename'],0,$file_chars_number,'UTF-8')."</a></li>";
	
	}
	$mybb_info .='<li><img src="'.plugins_url( 'core/refresh.png', __FILE__ ).'" class="refreshmli" id="topfile"></li>';
	$mybb_info.='</ul>';
	return $mybb_info;

	}
	public static function show_topref()
	{
	$table_prefix=get_option('mli_mybb_tableprefix');
	$mli_tref_num=get_option('mli_tref_num');
	$query_forum=MybbLastInfo::query("SELECT * FROM  ".$table_prefix."settings WHERE name='bburl'");
	$fetch_forum=mysqli_fetch_array($query_forum);
	$forum_url=$fetch_forum['value'];
	$query_reffer = MybbLastInfo::query("
	SELECT u.uid,u.username,u.usergroup,u.displaygroup,count(*) as refcount 
	FROM ".$table_prefix."users u 
	LEFT JOIN ".$table_prefix."users r ON (r.referrer = u.uid) 
	WHERE r.referrer = u.uid 
	GROUP BY r.referrer DESC 
	ORDER BY refcount DESC 
	LIMIT 0 ,{$mli_tref_num}");	
	$mybb_info='<ul id="mli_items">';
	while($fetch=mysqli_fetch_array($query_reffer))
	{
	$mybb_info .="<li><a href=".$forum_url."/member.php?action=profile&uid=".$fetch['uid']." target=\"_blank\">".$fetch['username']."</a></li>";
	}
	$mybb_info .='<li><img src="'.plugins_url( 'core/refresh.png', __FILE__ ).'" class="refreshmli" id="topref"></li>';
	$mybb_info.='</ul>';
	return $mybb_info;
	}
	
	public static function pcmli_widget()
	{
	$mybb_info='<div id="mybbinfoaccordion">';
	$fstat=get_option('mli_fstat');
	$lastpost_perm=get_option('mli_lastpost');
	$lastuser_perm=get_option('mli_lastuser');
	$mostviews_perm=get_option('mli_mostview');
	$hottopic_perm=get_option('mli_hottopic');
	$toprep_perm=get_option('mli_topreputation');
	$topfiles_perm=get_option('mli_topfiles');
	$topposter_perm=get_option('mli_topposter');
	$topreffer_perm=get_option('mli_reffer');
	if($fstat){$mybb_info.='<section id="item1"><h1><a href="#">'.__("Forum Statistics","mybblastinfo").'</a></h1><p>'.MybbLastInfo::show_forumstat().'</p></section>';}
	if($lastpost_perm){$mybb_info.='<section id="item2"><h1><a href="#">'.__("Last Post","mybblastinfo").'</a></h1><p>'.MybbLastInfo::show_lastpost().'</p></section>';}
	if($lastuser_perm){$mybb_info.='<section id="item3"><h1><a href="#">'.__("Last User","mybblastinfo").'</a></h1><p>'.MybbLastInfo::show_lastuser().'</p></section>';}
	if($mostviews_perm){$mybb_info.='<section id="item4"><h1><a href="#">'.__("Most viewed","mybblastinfo").'</a></h1><p>'.MybbLastInfo::show_mostviewed().'</p></section>';}
	if($hottopic_perm){$mybb_info.='<section id="item5"><h1><a href="#">'.__("Hot Topics","mybblastinfo").'</a></h1><p>'.MybbLastInfo::show_hottopics().'</p></section>';}
	if($topposter_perm){$mybb_info.='<section id="item6"><h1><a href="#">'.__("Top posting users","mybblastinfo").'</a></h1><p>'.MybbLastInfo::show_topposter().'</p></section>';}
	if($toprep_perm){$mybb_info.='<section id="item7"><h1><a href="#">'.__("Top Reputation","mybblastinfo").'</a></h1><p>'.MybbLastInfo::show_toprep().'</p></section>';}
	if($topfiles_perm){$mybb_info.='<section id="item8"><h1><a href="#">'.__("Top Files","mybblastinfo").'</a></h1><p>'.MybbLastInfo::show_topfile().'</p></section>';}
	if($topreffer_perm){$mybb_info.='<section id="item9"><h1><a href="#">'.__("Top Reffer","mybblastinfo").'</a></h1><p>'.MybbLastInfo::show_topref().'</p></section>';}
	$mybb_info.='</div>';
	echo $mybb_info;
	}
	public static function widget_init()
	{
		function mybblastinfo_widget($args)
		{
			extract($args);
			$options = get_option('mybblastinfo_widget');
			$title = $options['title'];
			echo $before_widget;
			echo $before_title . $title . $after_title;
			MybbLastInfo::pcmli_widget();
			echo $after_widget;
		}
		function mybblastinfo_widget_control()
		{
				$options = get_option('mybblastinfo_widget');
			if ( !is_array($options) )
				$options = array('title'=>'');
			if ( $_POST['pctrick_mybblastinfo_title_submit'] ) {
				$options['title'] = strip_tags(stripslashes($_POST['pctrick_mybblastinfo_title']));
				update_option('mybblastinfo_widget', $options);
			}
			$title = htmlspecialchars($options['title'], ENT_QUOTES);
			?>
			<p style="text-align:right; direction:rtl"><label for="pctrick_mybblastinfo_title"><?php _e("Title :","mybblastinfo");?> <input style="width: 200px;" id="pctrick_mybblastinfo_title" name="pctrick_mybblastinfo_title" type="text" value="<?php echo $title; ?>" /></label></p>
			<input type="hidden" id="pctrick_mybblastinfo_title_submit" name="pctrick_mybblastinfo_title_submit" value="1" />
			<?php
			}
		wp_register_sidebar_widget(87087,__("Mybb Last Information","mybblastinfo"),'mybblastinfo_widget');
		wp_register_widget_control(87087,__("Mybb Last Information","mybblastinfo"),'mybblastinfo_widget_control');		
	}
	public static function script()
	{
		wp_enqueue_style('mybblastinfo-css',plugins_url( 'core/mybblastinfo.css', __FILE__ ));
		wp_enqueue_script('jquery');
		wp_enqueue_script('mybblastinfo-js',plugins_url( 'core/mybblastinfo.js', __FILE__ ));
	}

}
?>