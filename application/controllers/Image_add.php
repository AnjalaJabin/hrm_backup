<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Image_add extends MY_Controller
{

    public function __construct()
    {
        Parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        //load the models
        $this->load->model("Employees_model");
        $this->load->model("Xin_model");

    }

    /*Function to set JSON output*/
    public function output($Return = array())
    {
        /*Set response header*/
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        /*Final JSON response*/
        exit(json_encode($Return));
    }
    public function add_document(){
        if(isset($_POST))
        {
            $this->load->library('SimpleImage');

            $root_id = $this->input->post('root_id');
            $user_id = $this->input->post('user_id');
            $employee_id = $this->input->post('employee_id');
            if(isset($_FILES['userfile']))
                $array = count($_FILES['userfile']['name']);
            else
                $array = count($_FILES['userfile2']['name']);
            $image_l=isset($_FILES['userfile'])?$_FILES['userfile']:$_FILES['userfile2'];
            for($j = 0; $j <= $array-1; $j++)
            {
                $wdth = 250;
                $hght = 200;

                $source_path = $image_l['tmp_name'][$j];
                $filename = $image_l['name'][$j];
                $filesize = $image_l['size'][$j];
                $act_img = $this->Xin_model->img_upload($source_path, $filename);
                $this->Xin_model->insertgal($filename, $act_img, $filesize, $root_id, $user_id, $employee_id);
            }
            $query = "SELECT * FROM xin_employee_document_files WHERE  (document_id='' OR document_id IS NULL ) and root_id = '$root_id' AND uid = '$user_id' ORDER BY id DESC";
            $result = $this->db->query($query);
            $html='';
            foreach($result->result() as $row)
            {

                $ximage = $row->img_name;

                $filename = $ximage;
                $file_url = base_url('uploads/document/'.$ximage);

                $ext = substr(strrchr($filename, '.'), 1);

                if($ext == "pdf")
                {
                    $imgico = "pdf-file.png";
                }
                else if($ext == "doc" || $ext == "docx")
                {
                    $imgico = "word_icon.png";
                    $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
                }
                else if($ext == "csv" || $ext == "xlsx" || $ext == "xls")
                {
                    $imgico = "excel_icon.png";
                    $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
                }
                else if($ext == "zip")
                {
                    $imgico = "zip_icon.png";
                }
                else
                {
                    $imgico = "img-file.png";
                }

//        <!--loop box images-->
                $html.=' <div class="loop-box">

        <div class="row">
        <div class="col-sm-3">
            <a target="blank" href="'.$file_url.'"><img src="'.base_url('skin/icons/'.$imgico).'" height="50" title="'.$row->img_list_name.'"/></a>
        </div>
        <div class="col-sm-6">
            <p><input name="txtcaption" type="text" class="input"';
                if($row->img_title){
                    $html.= 'value="'.$row->img_title.'" ';
                }  else {
                    $html.='placeholder="Caption Here"';
                }
                $html.= 'onchange="editcapt(this.value,"'.$row->id.'","'.$root_id.'","'.$employee_id.'")" /></p>
        </div>
 <div class="col-sm-3">
    	                    <a class="dbtn" href="javascript:delpic("'.$row->id.'","'.$root_id.'","'.$employee_id.'")"><span>Delete</span></a>
    	                </div>
    	            </div>

	            </div>
	            <div class="clear"></div>';
            }
            echo $html;

        }
    }
    public function e_add_document(){
        $this->load->library('upload');
        $this->load->helper('url');

        $root_id     = $this->input->post('root_id');
        $user_id     = $this->input->post('user_id');
        $document_id = $this->input->post('document_id');
        $employee_id = $this->input->post('employee_id');

        $array = count($_FILES['userfile2']['name']);
        $j = 0;
        while ($j <= $array-1) {
            $wdth = 250;
            $hght = 200;
            $source_path = $_FILES['userfile2']['tmp_name'][$j];
            $filename = $_FILES['userfile2']['name'][$j];
            $filesize = $_FILES['userfile2']['size'][$j];
            $act_img = $this->Xin_model->img_upload($source_path, $filename);
            $this->Xin_model->insertgal($filename, $act_img, $filesize, $root_id, $user_id, $document_id, $employee_id);
            $j = $j + 1;
        }

        $query = "SELECT * FROM xin_employee_document_files WHERE document_id='".$document_id."' AND root_id='".$root_id."' ORDER BY id DESC";
        $result = $this->db->query($query)->result_array();

        $loopBox = ''; // Initialize the loop box string

        foreach ($result as $row) {
            $ximage = $row['img_name'];
            $filename = $ximage;
            $file_url = site_url().'uploads/document/'.$ximage;
            $ext = substr(strrchr($filename, '.'), 1);

            if ($ext == "pdf") {
                $imgico = "pdf-file.png";
            } else if ($ext == "doc" || $ext == "docx") {
                $imgico = "word_icon.png";
                $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
            } else if ($ext == "csv" || $ext == "xlsx" || $ext == "xls") {
                $imgico = "excel_icon.png";
                $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
            } else if ($ext == "zip") {
                $imgico = "zip_icon.png";
            } else {
                $imgico = "img-file.png";
            }

            // Append the loop box HTML to the string
            $loopBox .= '
            <div class="loop-box">
                <div class="row">
                    <div class="col-sm-3">
                        <a target="_blank" href="'. $file_url .'">
                            <img src="'. site_url() .'skin/icons/'. $imgico .'" height="50" title="'. $row['img_list_name'] .'" />
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <p>
                            <input name="txtcaption" type="text" class="input" '. ($row['img_title'] ? 'value="'. $row['img_title'] .'"' : 'placeholder="Caption Here"') .' onchange="editcapt2(this.value,\''. $row['id'].'","'.$root_id.'","'.$employee_id.'")" /></p>
        </div>
 <div class="col-sm-3">
    	                    <a class="dbtn" href="javascript:delpic2("'.$row['id'].'","'.$root_id.'","'.$employee_id.'")"><span>Delete</span></a>
    	                </div>
    	            </div>

	            </div>
	            <div class="clear"></div>';
        }
        echo $loopBox;

    }


    public function edit_caption()
    {
        $this->load->helper('url');

        $img_id = $this->input->get('id');
        $caption = $this->input->get('cpt');
        $root_id = $this->input->get('root_id');
        $user_id = $this->input->get('user_id');
        $employee_id = $this->input->get('employee_id');

        $this->Xin_model->editcapt($img_id, $caption, $root_id, $user_id);

        $query = "SELECT * FROM xin_employee_document_files WHERE document_id IS NULL AND root_id='" . $root_id . "' AND employee_id='" . $user_id . "' ORDER BY id DESC";
        $result = $this->db->query($query)->result_array();

        $loopBox = ''; // Initialize the loop box string

        foreach ($result as $row) {
            $ximage = $row['img_name'];
            $filename = $ximage;
            $file_url = base_url() . 'uploads/document/' . $ximage;
            $ext = substr(strrchr($filename, '.'), 1);

            if ($ext == "pdf") {
                $imgico = "pdf-file.png";
            } else if ($ext == "doc" || $ext == "docx") {
                $imgico = "word_icon.png";
                $file_url = 'https://view.officeapps.live.com/op/view.aspx?src=' . $file_url;
            } else if ($ext == "csv" || $ext == "xlsx" || $ext == "xls") {
                $imgico = "excel_icon.png";
                $file_url = 'https://view.officeapps.live.com/op/view.aspx?src=' . $file_url;
            } else if ($ext == "zip") {
                $imgico = "zip_icon.png";
            } else {
                $imgico = "img-file.png";
            }

            // Append the loop box HTML to the string
            $loopBox .= '
            <div class="loop-box">
                <div class="row">
                    <div class="col-sm-3">
                        <a target="blank" href="' . $file_url . '">
                            <img src="' . base_url() . 'skin/icons/' . $imgico . '" height="50" title="' . $row['img_list_name'] . '" />
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <p>
                            <input name="txtcaption" type="text" class="input" ' . ($row['img_title'] ? 'value="' . $row['img_title'] . '"' : 'placeholder="Caption Here"') . ' onchange="editcapt(this.value,\'' . $row['id'] . '\',\'' . $root_id . '\',\'' . $employee_id . '\')" />
                        </p>
                    </div>
                    <div class="col-sm-3">
                        <a class="dbtn" href="javascript:delpic(\'' . $row['id'] . '\',\'' . $root_id . '\',\'' . $employee_id . '\')">
                            <span>Delete</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="clear"></div>';
        }

        // Return the loop box string
        echo $loopBox;
    }
    public function e_edit_caption() {
        $this->load->helper('url');

        $img_id = $this->input->get('id');
        $caption = $this->input->get('cpt');
        $root_id = $this->input->get('root_id');
        $user_id = $this->input->get('user_id');
        $document_id = $this->input->get('document_id');

        $this->Xin_model->editcapt($img_id, $caption, $root_id, $user_id);

        $query = "SELECT * FROM xin_employee_document_files WHERE document_id='".$document_id."' AND root_id='".$root_id."' ORDER BY id DESC";
        $result = $this->db->query($query)->result_array();

        $loopBox = ''; // Initialize the loop box string

        foreach ($result as $row) {
            $ximage = $row['img_name'];
            $filename = $ximage;
            $file_url = site_url().'uploads/document/'.$ximage;
            $ext = substr(strrchr($filename, '.'), 1);

            if ($ext == "pdf") {
                $imgico = "pdf-file.png";
            } else if ($ext == "doc" || $ext == "docx") {
                $imgico = "word_icon.png";
                $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
            } else if ($ext == "csv" || $ext == "xlsx" || $ext == "xls") {
                $imgico = "excel_icon.png";
                $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
            } else if ($ext == "zip") {
                $imgico = "zip_icon.png";
            } else {
                $imgico = "img-file.png";
            }

            // Append the loop box HTML to the string
            $loopBox .= '
            <div class="loop-box">
                <div class="row">
                    <div class="col-sm-3">
                        <a target="_blank" href="'. $file_url .'">
                            <img src="'. site_url() .'skin/icons/'. $imgico .'" height="50" title="'. $row['img_list_name'] .'" />
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <p>
                            <input name="txtcaption" type="text" class="input" '. ($row['img_title'] ? 'value="'. $row['img_title'] .'"' : 'placeholder="Caption Here"') .' onchange="editcapt2(this.value,\''. $row['id'] .'\',\''. $root_id .'\',\''. $user_id .'\',\''. $document_id .'\')" />
                        </p>
                    </div>
                    <div class="col-sm-3">
                        <a class="dbtn" href="javascript:delpic2(\''. $row['id'] .'\',\''. $root_id .'\',\''. $user_id .'\',\''. $document_id .'\')"><span>Delete</span></a>
                    </div>
                </div>
            </div>
            <div class="clear"></div>';
        }

        // Return the loop box string
        echo $loopBox;
    }

    public function delete_image() {

        $img_id  = $this->input->get('img_id');
        $root_id = $this->input->get('root_id');
        $user_id = $this->input->get('user_id');

        $this->Xin_model->delimg($img_id, $root_id, $user_id);

        $query = "SELECT * FROM xin_employee_document_files WHERE  (document_id='' OR document_id IS NULL ) and root_id = '$root_id' AND uid = '$user_id' ORDER BY id DESC";
        $result = $this->db->query($query);

        foreach($result->result() as $row)
        {
            $ximage = $row->img_name;

            $filename = $ximage;
            $file_url = base_url('uploads/document/'.$ximage);

            $ext = substr(strrchr($filename, '.'), 1);

            if($ext == "pdf")
            {
                $imgico = "pdf-file.png";
            }
            else if($ext == "doc" || $ext == "docx")
            {
                $imgico = "word_icon.png";
                $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
            }
            else if($ext == "csv" || $ext == "xlsx" || $ext == "xls")
            {
                $imgico = "excel_icon.png";
                $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
            }
            else if($ext == "zip")
            {
                $imgico = "zip_icon.png";
            }
            else
            {
                $imgico = "img-file.png";
            }

//        <!--loop box images-->
            $html=' <div class="loop-box">

        <div class="row">
        <div class="col-sm-3">
            <a target="blank" href="'.$file_url.'"><img src="'.base_url('skin/icons/'.$imgico).'" height="50" title="'.$row->img_list_name.'"/></a>
        </div>
        <div class="col-sm-6">
            <p><input name="txtcaption" type="text" class="input"';
            if($row->img_title){
                $html.= 'value="'.$row->img_title.'" ';
            }  else {
                $html.='placeholder="Caption Here"';
            }
            $html.= 'onchange="editcapt(this.value,"'.$row->id.'","'.$root_id.'","'.$user_id.'")" /></p>
        </div>
 <div class="col-sm-3">
    	                    <a class="dbtn" href="javascript:delpic("'.$row->id.'","'.$root_id.'","'.$user_id.'")"><span>Delete</span></a>
    	                </div>
    	            </div>

	            </div>
	            <div class="clear"></div>';
        }
        echo $html;


    }
 public function delpic_action() {

        $img_id  = $this->input->get('img_id');
        $root_id = $this->input->get('root_id');
        $user_id = $this->input->get('user_id');

        $this->Xin_model->delimg($img_id, $root_id, $user_id);

        $query = "SELECT * FROM xin_employee_document_files WHERE  (document_id='' OR document_id IS NULL ) and root_id = '$root_id' AND uid = '$user_id' ORDER BY id DESC";
        $result = $this->db->query($query);

        foreach($result->result() as $row)
        {
            $ximage = $row->img_name;

            $filename = $ximage;
            $file_url = base_url('uploads/document/'.$ximage);

            $ext = substr(strrchr($filename, '.'), 1);

            if($ext == "pdf")
            {
                $imgico = "pdf-file.png";
            }
            else if($ext == "doc" || $ext == "docx")
            {
                $imgico = "word_icon.png";
                $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
            }
            else if($ext == "csv" || $ext == "xlsx" || $ext == "xls")
            {
                $imgico = "excel_icon.png";
                $file_url = 'https://view.officeapps.live.com/op/view.aspx?src='.$file_url;
            }
            else if($ext == "zip")
            {
                $imgico = "zip_icon.png";
            }
            else
            {
                $imgico = "img-file.png";
            }

//        <!--loop box images-->
            $html=' <div class="loop-box">

        <div class="row">
        <div class="col-sm-3">
            <a target="blank" href="'.$file_url.'"><img src="'.base_url('skin/icons/'.$imgico).'" height="50" title="'.$row->img_list_name.'"/></a>
        </div>
        <div class="col-sm-6">
            <p><input name="txtcaption" type="text" class="input"';
            if($row->img_title){
                $html.= 'value="'.$row->img_title.'" ';
            }  else {
                $html.='placeholder="Caption Here"';
            }
            $html.= 'onchange="editcapt2(this.value,"'.$row->id.'","'.$root_id.'","'.$user_id.'")" /></p>
        </div>
 <div class="col-sm-3">
    	                    <a class="dbtn" href="javascript:delpic2("'.$row->id.'","'.$root_id.'","'.$user_id.'")"><span>Delete</span></a>
    	                </div>
    	            </div>

	            </div>
	            <div class="clear"></div>';
        }
        echo $html;


    }




}
