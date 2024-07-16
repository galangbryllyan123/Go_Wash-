<?php if(!empty($args['builder_content'])):?>
    <div class="tbp_advanced_archive_wrap">
	<?php
	    $builderId=$args['builder_id'];
            $isV7=method_exists('Themify_Builder_Model', 'add_module');//backward
	    
	    foreach ($args['builder_content'] as $rows => $row){
		if (!empty($row)) {
                    if($isV7===true){
                        Themify_Builder_Component_Row::template($row, $builderId);
                    }
                    else{
                        if ( ! isset( $row['row_order'] ) ) {
                                $row['row_order'] = $rows; 
                        }
                        Themify_Builder_Component_Row::template($rows, $row, $builderId, true);
                    }
		    
		}
	    }
	    $args=$builderId=$isV7=null;
	?>
    </div>
<?php endif;