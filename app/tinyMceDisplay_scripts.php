<?php 
$id = $_GET['id'];
$name = $_GET['name'];
?>
<script type="text/javascript" src="../lib/tinymce/tinymce.min.js"></script> 

<form >
    <textarea id='<?= $id?>' name='<?= $name ?>'style="width: 100%;height:100%">  
    </textarea>      
</form>

<style> 
    html,body{
        width: 100%;
        height: 98%;
        margin: 0px;
        overflow:hidden;
    } 
</style>

<script type="text/javascript">
//STORY EDITOR
    tinyMCE.init({
        // General options
        selector: 'textarea', 
        mode: "textareas",
        theme: "modern",
        plugins: [
        "save advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste emoticons textcolor colorpicker textpattern"
        ],
         toolbar1: "save | insertfile undo redo | styleselect | fontselect |  fontsizeselect | bold italic underline strikethrough | localautosave",
        toolbar2: "alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | forecolor backcolor emoticons",
        image_advtab: true,
        save_enablewhendirty: true,
        paste_data_images: true,
        autosave_ask_before_unload: true,
        media_live_embeds: true,
        external_filemanager_path: "/filemanager/",
        filemanager_title: "Responsive Filemanager",
        fontsize_formats: "9pt 10pt 11pt 12pt 13pt 14pt 15pt",
     
  
        save_onsavecallback: function () {
           parent.saveFilmScript();
        }

    });
   


</script>