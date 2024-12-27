<?php
/* Company view
*/
?>
<?php $session = $this->session->userdata('username');?>

<link rel="stylesheet" href="<?php echo site_url(); ?>skin/document/glrstyle.css">
<script type="text/javascript" src="<?php echo site_url(); ?>skin/document/ind.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo site_url(); ?>skin/document/jquery.form.js"></script>

<div class="add-form" style="display:none;">
  <div class="box box-block bg-white">
    <h2><strong><?php echo $this->lang->line('xin_add_new');?></strong> Document
      <div class="add-record-btn">
        <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-minus icon"></i> <?php echo $this->lang->line('xin_hide');?></button>
      </div>
    </h2>
    <div class="row m-b-1">
      <div class="col-md-12">
        <form method="post" name="add_document" id="xin-form" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo $session['user_id'];?>">
        <div class="bg-white">
          <div class="box-block">
            <div class="row">
                
              <div class="col-sm-4">
                  
                <div class="form-group">
                    <label for="company_name"><?php echo $this->lang->line('module_company_title');?></label>
                    <select class="form-control" name="company">
                      <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                      <?php foreach($all_companies as $company) {?>
                      <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                  
                <div class="form-group">
                    <label for="expiry_date">Document Expiry Date</label>
                    <input class="form-control expiry_date" readonly placeholder="Document Expiry Date" name="expiry_date" type="text" value="">
                </div> 
                
              </div>
                      
                
                
                
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="xin_document_title">Document Title</label>
                  <input class="form-control" placeholder="Document Title" name="document_title" type="text" id="doc_title" autocomplete="off">
                  <div id="suggesstion-box"></div>
                </div>
                
                <div class="form-group">
                  <label for="xin_des">Description</label>
                  <textarea class="form-control" placeholder="Description" name="description"></textarea>
                </div>
              </div>  
              
              
              <div class="col-sm-8">
                  <!--
                  <div class="form-group">
                    <h6>Document</h6>
                    <input type="file" name="file" id="file">
                    <br>
                    <small><?php echo $this->lang->line('xin_e_details_d_type_file');?></small>
                </div>
                -->
                
                <div class="uplpad-top-box"><a href="javascript:showfunc()"><span>+</span>Add Files</a></div> 
                            <div class="clear"></div>
        					<div id="progressbox">
        					    <div id="progressbar"></div>
        					    <div id="statustxt">0%</div>
        					</div>
        					
              </div>
              
              <div class="col-sm-8">
                        
                        <div class="box-body table-responsive">
        					
        					<div id="output">
        					
        					<?php
        	                foreach($all_files as $row) {
        					                
            	                $ximage=$row->img_name;
            	                
            	                $filename = $ximage;
            	                $file_url = site_url().'uploads/company_documents/'.$ximage;
            					                
            					$ext = substr(strrchr($filename, '.'), 1);
            					
            					if($ext=="pdf")
            					{
            					   $imgico="pdf-file.png";
            					}
            					else if($ext=="doc" || $ext=="docx")
            					{
            					   $imgico="word_icon.png";
            					   $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
            					}
            					else if($ext=="csv" || $ext=="xlsx" || $ext=="xls")
            					{
            					   $imgico="excel_icon.png";
            					   $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
            					}
            					else if($ext=="zip")
            					{
            					   $imgico="zip_icon.png";
            					}
            					else
            					{
            					   $imgico="img-file.png";
            					}
        					                
        					                
            	                ?>
            	                <!--loop box images-->
            	                <div class="loop-box">
            	                    
            	                    <div class="row">
            	                        <div class="col-sm-3">
                    	                    <a target="blank" href="uploads/company_documents/<?php echo $ximage; ?>"><img src="skin/icons/<?php echo $imgico; ?>" height="50" title="<?=$row->img_list_name; ?>"/></a>
                    	                </div>
                    	                <div class="col-sm-6">
                    	                    <p><input name="txtcaption" type="text" class="input" <?php if($row->img_title){ ?> value="<?php echo $row->img_title; ?>" <?php } else { ?> placeholder="Caption Here" <?php } ?> onchange="editcapt(this.value,'<?php echo $row->id; ?>','<?php echo $_SESSION['root_id']; ?>','<?php echo $_SESSION['user_id']; ?>')" /></p>
                    	                </div>
                    	                <div class="col-sm-3">
                    	                    <a class="dbtn" href="javascript:delpic('<?php echo $row->id; ?>','<?php echo $_SESSION['root_id']; ?>','<?php echo $_SESSION['user_id']; ?>')"><span>Delete</span></a>
                    	                </div>
                    	            </div>
                    	            
            	                    </div>
            	                    <div class="clear"></div>
            	                <?php
        	                }
        	                ?>
        					</div>
                        </div>
                    </div>
              
              <div class="col-sm-8">
                  <div class="text-right">
                        <br><br>
                      <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save');?> <i class="icon-circle-right2 position-right"></i> <i class="icon-spinner3 spinner position-left"></i></button>
                    </div>
              </div>
             
            </div>
          </div>
        </div>
      </form>
      
        
                    <form action="<?php echo site_url(); ?>documents_upload/controller/img_add_action.php" method="post" enctype="multipart/form-data" name="UploadForm" id="UploadForm">
					 <input type="hidden" name="root_id" value="<?php echo $_SESSION['root_id']; ?>"/>
					 <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>"/>
					 <input name="userfile[]" id="userfile" type="file" style="display:none" multiple accept="image/x-png, image/gif, image/jpeg, .pdf,.doc,.docx,.zip,.xlsx,.csv,.xls" onChange="actfunc()"  required />
					 <input type="submit"  id="SubmitButton" value="Upload" style="display:none;"/>
					</form>
      </div>
    </div>
  </div>
</div>
<div class="box box-block bg-white">
  <h2><strong><?php echo $this->lang->line('xin_list_all');?></strong> Documents
    <div class="add-record-btn">
      <button class="btn btn-sm btn-primary add-new-form"><i class="fa fa-plus icon"></i> <?php echo $this->lang->line('xin_add_new');?></button>
    </div>
  </h2>
  <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table" style="width:100%;">
      <thead>
        <tr>
          <th><?php echo $this->lang->line('xin_action');?></th>
          <th>Title</th>
          <th>Expiry</th>
          <th>Company</th>
          <th><?php echo $this->lang->line('xin_added_by');?></th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<style>
    #title-list{float:left;list-style:none;margin-top:-3px;padding:0;width:93%;position: absolute;}
    #title-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
    #title-list li:hover{background:#ece3d2;cursor: pointer;}
</style>
