 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<div class="pcoded-content">
	<div class="pcoded-inner-content">
		<div class="main-body">
			<div class="page-wrapper"> 
				<!-- Page-header start -->
				<div class="page-header">
					<div class="row align-items-end">
						<div class="col-lg-8">
							<div class="page-header-title">
								<div class="d-inline">
									<h4><?php echo $page_title; ?></h4>
									<span>Welcome sub-title</span>
								</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="page-header-breadcrumb">
								<ul class="breadcrumb-title">
									<li class="breadcrumb-item">
										<a href=""> <i class="fa fa-cogs"></i> </a>
									</li>
									<li class="breadcrumb-item"><a href="#!">Admin</a>
									</li>
									<li class="breadcrumb-item"><a href=""><?php echo $page_title; ?></a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<!-- Page-header end -->
				<div class="page-body">
					<div class="row">
						<div class="card col-md-12">
							<div class="card-header">
								<h5><?php echo $page_title; ?></h5>
								<div class="card-header-right">
									<ul class="list-unstyled card-option">
										
										<li><i class="feather icon-maximize full-card"></i></li>
										<li><i class="feather icon-minus minimize-card"></i></li>
										<li><i class="feather icon-trash close-card"></i></li>
									</ul>
								</div>
								<hr>
								<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------->
		<ul class="nav nav-tabs bordered">
        	<?php if(isset($edit_profile)):?>
			<li class="active">
            	<a  class="nav-link" href="#edit" data-toggle="tab"><i class="icon-wrench"></i> 
					<?php echo get_phrase('edit_phrase');?>
                    	</a></li>
            <?php endif;?>
			<li class="nav-item <?php if(!isset($edit_profile))echo 'active';?>">
            	<a class="" href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('language_list');?>
                    	</a></li>
			<li>
            	<a class="" href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_phrase');?>
                    	</a></li>
			<li class="">
            	<a class="" href="#add_lang" data-toggle="tab"><i class="entypo-plus-circled"></i> 
					<?php echo get_phrase('add_language');?>
                    	</a></li>
		</ul>
    	<!------CONTROL TABS END------->
        
	
		<div class="tab-content">
            <!----PHRASE EDITING TAB STARTS-->
            <?php if (isset($edit_profile)):?>
			<div class="tab-pane active" id="edit" style="padding: 5px">
                <div class="">


						<div class="row">
                    	<?php 
						$current_editing_language	=	$edit_profile;
						echo form_open('admin/manage_language/update_phrase/'.$current_editing_language  , array('id' => 'phrase_form'));
						$count = 0;
						$language_phrases	=	$this->db->query("SELECT `phrase_id` , `phrase` , `$current_editing_language` FROM `system_language`")->result_array();
						foreach($language_phrases as $row)
						{
							$phrase_id			=	$row['phrase_id'];					//id number of phrase
							$phrase				=	$row['phrase'];						//basic phrase text
							$phrase_language	=	$row[$current_editing_language];	//phrase of current editing language
							$count++;													//no-of-phrase
							?>
                            <!----phrase box starts-->
                            <div class="col-sm-3">
                                <div class="tile-stats tile-gray">
                                    <div class="icon"><i class="entypo-mail"></i></div>
                                    
                                    
                                    <h3><?php echo $row['phrase'];?></h3>
                                    <p>
                                    	<input type="text" name="phrase<?php echo $row['phrase_id'];?>" 	
                                    		value="<?php echo $phrase_language;?>" class="form-control"/>
                                    </p>
                                </div>
                                
                            </div>
                            <!----phrase box ends-->
							<?php 
						}
						?>
						</div>
                        <input type="hidden" name="total_phrase" value="<?php echo $count;?>" />
                        <input type="submit" value="<?php echo get_phrase('update_phrase');?>" onClick="document.getElementById('phrase_form').submit();" class="btn btn-blue"/>	
                        <?php
						echo form_close();
						?>
                                     
                </div>                
			</div>
            <?php endif;?>
            <!----PHRASE EDITING TAB ENDS-->
            <!----TABLE LISTING STARTS--->
            <div class="tab-pane <?php if(!isset($edit_profile))echo 'active';?>" id="list">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                	<thead>
                    	<tr>
                        	<th><?php echo get_phrase('language');?></th>
                        	<th><?php echo get_phrase('option');?></th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php
								$fields = $this->db->list_fields('system_language');
								foreach($fields as $field)
								{
									 if($field == 'phrase_id' || $field == 'phrase')continue;
									?>
                    	<tr>
                        	<td><?php echo ucwords($field);?></td>
                        	<td>
                            	<a href="<?php echo base_url();?>admin/manage_language/edit_phrase/<?php echo $field;?>"
                                	 class="btn btn-info">
                                		<?php echo get_phrase('edit_phrase');?>
                                </a>
                            	<a class="btn- btn-danger btn-lg" href="<?php echo base_url();?>admin/manage_language/delete_language/<?php echo $field;?>"
                                	rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('delete_language');?>" class="btn btn-gray" onclick="return confirm('Delete Language ?');">
                                		<i class="fa fa-trash "></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS--->
            
            
			<!----PHRASE CREATION FORM STARTS---->
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                    <?php echo form_open('admin/manage_language/add_phrase/' , array('class' => 'form-horizontal form-groups-bordered validate'));?>
                        <div class="padded">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('phrase');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="phrase" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_phrase');?></button>
                              </div>
							</div>
                    <?php echo form_close();?>                
                </div>                
			</div>
			<!----PHRASE CREATION FORM ENDS--->
            
        	<!----ADD NEW LANGUAGE---->
			<div class="tab-pane box" id="add_lang" style="padding: 5px">
                <div class="box-content">
                    <?php echo form_open('admin/manage_language/add_language/' , array('class' => 'form-horizontal form-groups-bordered validate'));?>
                        <div class="padded">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('language');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="language" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_language');?></button>
                              </div>
							</div>
                    <?php echo form_close();?> 
                </div>
			</div>
            <!----LANGUAGE ADDING FORM ENDS--->
            
		</div>
	</div>
</div>
							</div>
							
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	
</div>

	