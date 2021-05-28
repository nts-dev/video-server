const upload_title = "File Picker"
const library_title = "Local Library"
const audio_text_title = "Text Translation"
const video_cut_url = "app/videoCutService/data.php?action="


const maxUploadFormArea = myWidth * 0.5;
const maxUploadFormAreaPaddingSide = myWidth * 0.1;
const maxUploadFormAreaPaddingTop = myHeight * 0.04;


// const main_layout = .attachLayout('1C');


const upload_layout = parentModuleTabbar.tabs("videoCut").attachLayout('3T');
upload_layout.cells('a').setText(upload_title);
upload_layout.cells('b').setText(library_title);
upload_layout.cells('c').setText(audio_text_title);
upload_layout.cells('c').collapse();
upload_layout.cells('a').setHeight(myHeight * 0.3);
upload_layout.cells('c').setWidth(myWidth * 0.1);


const cutUploadBoxFormData = [
    {
        type: "block", list: [
            {type: "settings", offsetTop: maxUploadFormAreaPaddingTop, offsetLeft: maxUploadFormAreaPaddingSide},
            {
                type: "fieldset",
                list: [{
                    type: "upload",
                    name: "upload_file",
                    inputWidth: maxUploadFormArea,
                    // url: url +"1&name=" + form_details_obj.name + "&start=" + form_details_obj.start + "&end=" + form_details_obj.end,
                    swfPath: "http://" + location.host + "/dhtmlxSuite5/codebase/ext/uploader.swf"
                }]

            }
        ]
    }
];

const cutUploadFileForm = upload_layout.cells('a').attachForm(cutUploadBoxFormData);

cutUploadFileForm.attachEvent("onFileAdd", function (realName) {
    const parent = (projectId) ? projectId : courses_grid.getSelectedItemId();
    const accepted = ["mp3", "mp4", "ebm"];
    const ext = realName.substring(realName.length - 3, realName.length).toLowerCase();

    if (!accepted.includes(ext)) {
        dhtmlx.alert({title: "Error", text: realName + " should be of type mp3/4 or webm"})
        clearForm();

        return;
    }

    if (parent && accepted.includes(ext)) {
        showUploadForm(cutUploadFileForm, courses_grid.getItemText(parent), parent);
    } else {
        dhtmlx.alert({title: "Error", text: "Please select directory from tree"})
        clearForm();
    }
});


function clearForm() {
    const myUploader = cutUploadFileForm.getUploader('upload_file');
    myUploader.clear();
}

cutUploadFileForm.attachEvent("onUploadComplete", function (count) {
    dhtmlx.message("Upload complete");
    clearForm();
    mediaGrid.clearAndLoad(video_cut_url + "2&project=" + trainingId);
});

cutUploadFileForm.attachEvent("onUploadFail", function (name) {
    dhtmlx.alert({title: "Error", text: "There was an error while uploading " + name})
});


const videoCutToolbar = upload_layout.cells('b').attachToolbar();
videoCutToolbar.setIconset("awesome");
videoCutToolbar.loadStruct('<toolbar>'

    + '<item type="button" id="delete" text="Delete" img="fa fa-trash " /><item type="separator" id="sep_4" />'
    + '<item type="button" id="translate" text="Translate to text" img="fa fa-assistive-listening-systems" /><item type="separator" id="sep_1" />'
    + '</toolbar>');
videoCutToolbar.attachEvent("onClick", onVideoCutToolbarClicked);

function onVideoCutToolbarClicked(id) {
    switch(id){
        case 'delete':
            deleteAudioItem();
            break;

        case 'translate':
            expandOrCollapseTextLayout();
            break;
    }        
}

function expandOrCollapseTextLayout(){
    const state = upload_layout.cells('c').isCollapsed();
    if(state) upload_layout.cells('c').expand();
    else upload_layout.cells('c').collapse();
}

function deleteAudioItem(){
    $.ajax({
        url: video_cut_url + "3",
        type: "GET",
        data: {id: mediaGrid.getSelectedRowId()},
        success: function (response) {
            const parsedJSON = eval('(' + response + ')');
            if (parsedJSON != null) {
                dhtmlx.message({title: 'Success', text: parsedJSON.message});
                mediaGrid.clearAndLoad(video_cut_url + "2&project=" + trainingId);
            }
        }
    });
} 


const mediaGrid = upload_layout.cells('b').attachGrid();
mediaGrid.setHeader("ID, Title, Start time, End time, Link (Copy this to embed in moodle), date");
mediaGrid.setInitWidthsP("7,10,8,8,*,10");
mediaGrid.enableAutoWidth(false);

mediaGrid.init();

// mediaGrid.loadXML(video_cut_url + "2");




/****
 * 
 * 
 * Speech text editor
 * 
 */


const speechTextEditorToolbar = upload_layout.cells('c').attachToolbar();
speechTextEditorToolbar.setIconset("awesome");
speechTextEditorToolbar.loadStruct('<toolbar>'

    + '<item type="button" id="save" text="Save" img="fa fa-save " /><item type="separator" id="sep_4" />'
    + '</toolbar>');


 const speechTextEditor = upload_layout.cells('c').attachEditor();
