<?php 
$user_id = $this->session->userdata('user_id');
$result  = $this->Contact_model->get_contact_group($user_id); 
?>
    <label for="email">Contact Group</label>
      <select class="form-control" name="contact_group" data-placeholder="Contact Group">
        <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
        <?php foreach($result as $group) {?>
        <option value="<?php echo $group->id;?>"> <?php echo $group->name;?></option>
        <?php } ?>
      </select>
    