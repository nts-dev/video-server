function showSplitForm(state) {

    if(state != null ) {
        const fileUploadMainWindow = new dhtmlXWindows();
        const fileUploadWindow = fileUploadMainWindow.createWindow({
            id: "uploadfile_win",
            height: 250,
            width: 450,
            modal: true,
            resize: false,
            park: false
        });
        fileUploadWindow.center();
        fileUploadWindow.setText("Split duration");
        fileUploadWindow.bringToTop();
        fileUploadWindow.show();

        const fileUploadLayout = fileUploadWindow.attachLayout('1C');
        fileUploadLayout.cells('a').hideHeader();

        const contentForm = fileUploadLayout.cells('a').attachForm(split_media_formData);
        contentForm.attachEvent("onButtonClick", function (name) {
            if (name === "submit" && contentForm.validate()) {

                if (isFormItemValid(contentForm.getItemValue('start'), contentForm.getItemValue('end'))) {
                    uploaderLayout.cells('b').progressOn();
                    $.ajax({
                        url: video_cut_url + "5",
                        type: "POST",
                        data: {
                            project: state.project,
                            start: contentForm.getItemValue('start'),
                            end: contentForm.getItemValue('end'),
                            parent: state.id,
                            name: state.name,
                            url: state.url,
                        },
                        success: function (response) {
                            const parsedJSON = eval('(' + response + ')');
                            if (parsedJSON != null) {
                                dhtmlx.message({title: 'Success', text: parsedJSON.message});
                                mediaTreeGrid.clearAndLoad(video_cut_url + "6&project=" + state.project);
                                fileUploadWindow.close();
                                uploaderLayout.cells('b').progressOff();
                            }else
                                uploaderLayout.cells('b').progressOff();
                        }
                    });
                } else
                    contentForm.setNote("end", {
                        text: "Start time can not exceed end time.", required: true
                    });
            }
        });
        contentForm.attachEvent("onEnter", function (id, value) {
            contentForm.validate();
        });
    }

}


const split_media_formData = [
    {type: "settings", position: "label-left", labelWidth: 100, inputWidth: 180, offsetLeft: 35},

    {type: "input", label: "ID", className: "formlabel", name: "ID", hidden: true},
    {
        type: "input",
        label: "Start time",
        className: "formlabel",
        name: "start",
        required: true,
        validate: "([0-5][0-9][:])[0-5][0-9]",
        note: {
            text: "Please use [00:04, 01:40] formats"
        }
    },
    {
        type: "input",
        label: "End time",
        className: "formlabel",
        name: "end",
        required: true,
        validate: "([0-5][0-9][:])[0-5][0-9]",
        note: {
            text: "Please use [00:04, 01:40] formats. \n Must be greater than start time"
        }
    },
    {type: "label", name: "valid", label: ""},
    {type: "button", label: "Continue", className: "formlabel", value: "Continue", name: "submit"}
];


function isFormItemValid(start, end) {
    return (end > start);
}


