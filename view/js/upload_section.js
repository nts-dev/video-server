const content_category_title = "Content Category";
const upload_title = "File Picker"
const audio_text_title = "Text Translation"
const video_cut_url = baseURL+ "/app/videoCutService/data.php?action="

const maxUploadFormArea = myWidth * 0.5;
const maxUploadFormAreaPaddingSide = myWidth * 0.1;
const maxUploadFormAreaPaddingTop = myHeight * 0.03;

const mediaTreeGridState = {id: -1, name: "", project: "", url: ""};


const uploaderLayout = parentModuleTabbar.tabs("uploader").attachLayout('3T');
uploaderLayout.cells('a').setText(upload_title);
uploaderLayout.cells('b').setText(content_category_title);
uploaderLayout.cells('c').setText(audio_text_title);
uploaderLayout.cells('c').collapse();
uploaderLayout.cells('a').setHeight(myHeight * 0.25);
uploaderLayout.cells('c').setWidth(myWidth * 0.1);


const uploaderBoxFormData = [
    {
        type: "block", list: [
            {type: "settings", offsetTop: maxUploadFormAreaPaddingTop, offsetLeft: maxUploadFormAreaPaddingSide},
            {
                type: "fieldset",
                list: [{
                    type: "upload",
                    name: "upload_file",
                    inputWidth: maxUploadFormArea,
                    inputHeight: 20,
                    // url: url +"1&name=" + form_details_obj.name + "&start=" + form_details_obj.start + "&end=" + form_details_obj.end,
                    swfPath: "http://" + location.host + "/dhtmlxSuite5/codebase/ext/uploader.swf"
                }]

            }
        ]
    }
];

const uploaderFileForm = uploaderLayout.cells('a').attachForm(uploaderBoxFormData);

const myUploader = uploaderFileForm.getUploader("upload_file");




uploaderFileForm.attachEvent("onFileAdd", function (realName) {
    const parent = projectFromProject.id
    const accepted = ["mp3", "mp4", "ebm"];
    const ext = realName.substring(realName.length - 3, realName.length).toLowerCase();

    if (!accepted.includes(ext)) {
        dhtmlx.alert({title: "Error", text: realName + " should be of type mp3/4 or webm"})
        clearForm();

        return;
    }

    if (parent && accepted.includes(ext)) {
        /***
         *
         * Change to form before upload
         *
         */
        // showSplitForm(cutUploadFileForm, courses_grid.getItemText(parent), parent);
        _uploadMediaFile(myUploader, projectFromProject.title, parent)
    } else {
        dhtmlx.alert({title: "Error", text: "Please select directory from tree"})
        clearForm();
    }
});

function _uploadMediaFile(form, project_name, project_id) {
    form.setURL(video_cut_url + "4&name=" + project_name + "&project=" + project_id);
    form.upload();
}

function clearForm() {
    uploaderFileForm.getUploader('upload_file').clear();
}

uploaderFileForm.attachEvent("onUploadComplete", function (count) {
    dhtmlx.message("Upload complete");
    clearForm();
    mediaTreeGrid.clearAndLoad(video_cut_url + "6&project=" + mediaTreeGridState.project);
});

uploaderFileForm.attachEvent("onUploadFail", function (name) {
    dhtmlx.alert({title: "Error", text: "There was an error while uploading " + name})
});


const uploaderContentToolbar = uploaderLayout.cells('b').attachToolbar();
uploaderContentToolbar.setIconset("awesome");
uploaderContentToolbar.loadStruct('<toolbar>'

    + '<item type="button" id="split" text="Split" img="fa fa-clone " /><item type="separator" id="sep_2" />'
    + '<item type="button" id="delete" text="Delete" img="fa fa-trash " /><item type="separator" id="sep_4" />'
    + '<item type="button" id="translate" text="Translate to text" img="fa fa-assistive-listening-systems" /><item type="separator" id="sep_1" />'
    + '</toolbar>');
uploaderContentToolbar.attachEvent("onClick", onUploaderToolbarClicked);
uploaderContentToolbar.disableItem('split');

function onUploaderToolbarClicked(id) {
    switch (id) {
        case 'delete':
            if(mediaTreeGrid.getSelectedRowId())
                deleteAudioItem();
            else dhtmlx.alert("Please select record")
            break;

        case 'translate':
            // expandOrCollapseTextLayout();
            break;

        case 'split':
            spiltItem();
            break;
    }
}

function spiltItem() {

    const state = mediaTreeGridState;
    if(state.id == null || state.project == null){
        dhtmlx.alert("Please select project")
        return;
    }

    showSplitForm(state)

}

function expandOrCollapseTextLayout() {
    const state = uploaderLayout.cells('c').isCollapsed();
    if (state) uploaderLayout.cells('c').expand();
    else uploaderLayout.cells('c').collapse();
}

function deleteAudioItem() {
    uploaderLayout.cells('b').progressOn();
    $.ajax({
        url: video_cut_url + "7",
        type: "GET",
        data: {
            id: mediaTreeGridState.id,
            is_parent : mediaTreeGrid.getLevel(mediaTreeGrid.getSelectedRowId()),
            url: mediaTreeGridState.url
        },
        success: function (response) {
            const parsedJSON = eval('(' + response + ')');
            if (parsedJSON != null) {
                dhtmlx.message({title: 'Success', text: parsedJSON.message});
                mediaTreeGrid.clearAndLoad(video_cut_url + "6&project=" + mediaTreeGridState.project);
                uploaderLayout.cells('b').progressOff();
            }
        }
    });
}


const mediaTreeGrid = uploaderLayout.cells('b').attachGrid();
mediaTreeGrid.setSkin(grid_skin);
mediaTreeGrid.setImagePath('lib/dhtmlxSuite5/skins/web/imgs/');
mediaTreeGrid.setHeader("Title, Link (Copy this to embed in moodle), Start time, End time, date");
mediaTreeGrid.setInitWidthsP("25,*,8,8,10");
mediaTreeGrid.setColTypes('tree,ed,ro,ro,ro');
mediaTreeGrid.enableAutoWidth(false);
mediaTreeGrid.init();

mediaTreeGrid.loadXML(video_cut_url + "6&project=" + mediaTreeGridState.project);


mediaTreeGrid.attachEvent("onRowSelect", function (id, ind) {
    mediaTreeGrid.getLevel(id) === 0 ? uploaderContentToolbar.enableItem('split') : uploaderContentToolbar.disableItem('split')

    mediaTreeGridState.id = id;
    mediaTreeGridState.name = mediaTreeGrid.getItemText(id);
    mediaTreeGridState.project = (PROJECT_ID) ? PROJECT_ID : courses_grid.getSelectedItemId();
    mediaTreeGridState.url = mediaTreeGrid.cells(id,1).getValue();

    console.log(mediaTreeGridState)

});

