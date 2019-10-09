<?php
/**
 * WAI Multi Category Copying Main
 * 
 * main script
 * 
 */

class MCC {
	public $_wpdb;

	public function __construct() {
		global $wpdb;
		$this->_wpdb = $wpdb;
		register_activation_hook(WAI_MCC_FILE, array($this, 'activation'));
		register_deactivation_hook(WAI_MCC_FILE, array($this, 'deactivation'));

		add_action('admin_menu', array($this, '__register_mcc_page'));
	}

	public function activation() {
		// アクティベーション
	}

	public function deactivation() {
		// デアクティベーション
	}

	
	/**
	 * メニュー追加
	 */
	public function __register_mcc_page() {
		add_menu_page(
			__('Multi category copy'),
			__('MS Category copy'),
			'manage_options',
			'multi_category_copy',
			array($this, '__register_mcc_page_action'),
			'',
			200
		);
	}
	public function __register_mcc_page_action() {
		if(! is_multisite()){
			echo ('マルチサイトでのみご利用いただけます。');
		} else {
			$query = 'select blog_id from '.$this->_wpdb->blogs;
			$blogs = $this->_wpdb->get_results($query);
			if(1 >= count($blogs)){
				echo ('マルチサイトは複数定義されている必要があります。');
			} else {
				if($_POST){
					$this->__register_mcc_page_update();
				}
				$this->__register_mcc_page_code($blogs);
			}
		}
	}
	public function __register_mcc_page_code($blogs=null) {
		$sites = array();
		$query = 'select taxonomy from '.$this->_wpdb->term_taxonomy.' group by taxonomy';
		$terms = $this->_wpdb->get_results($query);
		foreach($blogs as $b){
			if($b->blog_id == 1) continue;
			$tmp_query = 'select option_value from wp_'.$b->blog_id.'_options where option_name = "blogname"';
			$tmp_blog = $this->_wpdb->get_results($tmp_query);
			$sites[$b->blog_id] =  $tmp_blog[0]->option_value;
		}
		require_once dirname(WAI_MCC_FILE).'/tpl/multi-category-copy.php';
	}
	public function __register_mcc_page_update(){
		
		// get origin category
		$query = '
			select 
				T1.term_id,T1.name,T1.slug,T2.taxonomy 
			from 
				'.$this->_wpdb->terms.' as T1 
			inner join 
				'.$this->_wpdb->term_taxonomy.' as T2 on T1.term_id = T2.term_id 
			where 
				T2.taxonomy = "'.$_POST['base_term_id'].'"';

		$records = $this->_wpdb->get_results($query);

		// update blog terms
		if(!empty($_POST['child_blog_id'])) {
			foreach($_POST['child_blog_id'] as $child_blog_id){
				switch_to_blog($child_blog_id);
				foreach($records as $r){
					
					if(!empty(get_term_by('id',$r->term_id,$r->taxonomy))){
						wp_update_term($r->term_id,$r->taxonomy,array(
							'name' => $r->name,
							'slug' => $r->slug,
						));
					} else {
						wp_insert_term($r->name,$r->taxonomy,array(
							'slug' => $r->slug,
						));
					}

				}
			}
		}
		switch_to_blog(1);

		// 更新できたかどうかのステータスを返す
	}
}
new MCC();