// JavaScript Document
$(document).ready(function(){
    //elements
    var progressbox     = $('#progressbox');
    var progressbar     = $('#progressbar');
    var statustxt       = $('#statustxt');
    var submitbutton    = $("#SubmitButton");
    var myform          = $("#UploadForm");
    var output          = $("#output");
    var completed       = '0%';
 
    $(myform).ajaxForm({
        beforeSend: function() { //brfore sending form
        //submitbutton.attr('disabled', ''); // disable upload button
        statustxt.empty();
        progressbox.slideDown(); //show progressbar
        progressbar.width(completed); //initial value 0% of progressbar
        statustxt.html(completed); //set status text
        statustxt.css('color','#000'); //initial color of status text
        },
        uploadProgress: function(event, position, total, percentComplete) { //on progress
        progressbar.width(percentComplete + '%') //update progressbar percent complete
        statustxt.html(percentComplete + '%'); //update status text
        if(percentComplete>50)
        {
            statustxt.css('color','#fff'); //change status text to white after 50%
        }
        },

        complete: function(response) { // on complete
        output.html(response.responseText); //update element with received data
        
        myform.resetForm();  // reset form
        // submitbutton.removeAttr('disabled'); //enable submit button
        progressbox.slideUp(); // hide progressbar
        }
    });
});



function showfunc()
{
document.getElementById('userfile').click();
}

function actfunc(cnt_id)
{
sts=0;
var files = document.getElementsByName('userfile[]'); 

for (var i = 0, j = files.length; i < j; i++) {
					var file = files[i];


fname=file.value;
var re = /(\.jpg|\.jpeg|\.bmp|\.gif|\.png|\.pdf|\.zip|\.doc|\.docx|\.zip|\.csv|\.xlsx|\.xls)$/i;
if(!re.exec(fname))
{
var sts=1;
}

}
var vl=document.getElementById('userfile').value;
	if(vl!='')
	{

		if(sts==1)
		{
		alert('File Type Not Supported');
		}
		else
		{
		//document.getElementById('SubmitButton').click();
		$("#UploadForm").submit();
		//document.getElementById('SubmitButton').click();
		//document.UploadForm.action='gallery/controller/img_add_action.php';
		//document.frmimg.submit();
		}
	}
	
}
//function editcapt(capt,id)
//{
//document.location="controller/edit_caption.php?id="+id+"&cpt="+capt;
//}
function editcapt(capt,id,root_id,user_id)
{
	if (window.XMLHttpRequest)   
	{
		xmlhttp=new XMLHttpRequest(); 
	}
	else   
	{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	} 
	xmlhttp.onreadystatechange=function()   
	{ 
		if (xmlhttp.readyState==4 && xmlhttp.status==200)     
		{
		document.getElementById("output").innerHTML=xmlhttp.responseText;
			//window.location.reload();
		}   
	}
	xmlhttp.open("GET","<?php echo site_url(); ?>image_add/edit_caption?id="+id+"&cpt="+capt+"&root_id="+root_id+"&user_id="+user_id,true);
	xmlhttp.send();
}
function delpic(img_id,root_id,user_id)
{
    if(confirm("Are you sure you want to delete this file?")) {
    	if (window.XMLHttpRequest)   
    	{
    		xmlhttp=new XMLHttpRequest(); 
    	}
    	else   
    	{
    		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    	} 
    	xmlhttp.onreadystatechange=function()   
    	{
    		if (xmlhttp.readyState==4 && xmlhttp.status==200)     
    		{
    		document.getElementById("output").innerHTML=xmlhttp.responseText;
    		}   
    	}
    	xmlhttp.open("GET","<?php echo site_url(); ?>image_add/delete_image?img_id="+img_id+"&root_id="+root_id+"&user_id="+user_id,true);
    	xmlhttp.send();
    }
}