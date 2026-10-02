<?php 
if(function_exists('get_field_objects')){
    $fields = get_field_objects();
    if( $fields ){
    	uasort($fields,'mbt_compare_field');
    	echo '<div class="article-custom-metas clearfix">';
        foreach( $fields as $field_name => $field ){
        	if($field['value']){
                echo '<div class="meta">';
                echo '<t>' . $field['label'] . '：</t>';
                if(is_array($field['value'])){
                	if($field['type'] == 'link'){
                		echo '<a href="'.$field['value']['url'].'" target="'.$field['value']['target'].'">'.$field['value']['title'].'</a>';
                	}elseif($field['type'] == 'taxonomy'){
                		$tax_html = '';
                		foreach ($field['value'] as $tax) {
                			$term = get_term_by('term_id',$tax,$field['taxonomy']);
                			$tax_html .= '<a href="'.get_term_link($tax).'" target="_blank">'.$term->name.'</a>, ';
                		}
                		echo rtrim($tax_html, ', ');
                	}elseif($field['type'] == 'relationship' || $field['type'] == 'post_object'){
                		foreach ($field['value'] as $postr) {
							$field_value = mbt_object_to_array($postr);
                    		echo '<a href="'.get_permalink($field_value['ID']).'" target="_blank">'.$field_value['post_title'].'</a> ';
                    	}
                    }else{
						echo implode(',', $field['value']);
					}
				}elseif(is_object($field['value'])){
					if($field['type'] == 'post_object'){
						$field_value = mbt_object_to_array($field['value']);
                		echo '<a href="'.get_permalink($field_value['ID']).'" target="_blank">'.$field_value['post_title'].'</a>';
                	}
				}else{
					if($field['type'] == 'radio'){
						$vv = $field['value'];
						echo $field['choices'][$vv];
					}else{
						if(isset($field['append'])){
							echo $field['value'].$field['append'];
						}else{
							echo $field['value'];
						}
					}
				}
                echo '</div>';
            }
        }
        echo '</div>';
    }
}
?>