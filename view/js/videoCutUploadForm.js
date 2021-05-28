let form_details_obj = {name: "", start: "", end: ""};

function showUploadForm(uploadForm, gridItemName, projectId) {

    if (uploadForm) {
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
        fileUploadWindow.setText("File Details");
        fileUploadWindow.bringToTop();
        fileUploadWindow.show();

        const fileUploadLayout = fileUploadWindow.attachLayout('1C');
        fileUploadLayout.cells('a').hideHeader();

        const contentForm = fileUploadLayout.cells('a').attachForm(cut_content_formData);
        contentForm.attachEvent("onButtonClick", function (name) {
            // your code here
            if (name === "submit" && contentForm.validate()) {

                form_details_obj.start = contentForm.getItemValue('start');
                form_details_obj.end = contentForm.getItemValue('end');
                form_details_obj.name = gridItemName;

                if(isFormItemValid(contentForm.getItemValue('start'), contentForm.getItemValue('end'))) {
                    const myUploader = uploadForm.getUploader("upload_file");
                    myUploader.setURL(video_cut_url +"1&name=" + form_details_obj.name + "&start=" + form_details_obj.start + "&end=" + form_details_obj.end + "&project=" + projectId)
                    myUploader.upload();
                    console.log(form_details_obj)
                    fileUploadWindow.close();
                }else
                    contentForm.setNote("end", {
                        text: "Start time can not exceed end time.", required: true
                    });

            }

        });

        contentForm.attachEvent("onEnter", function(id, value) {
            contentForm.validate();
        });
    }
}



/***
 *
 *
 *
 * check letter
 * @param obj
 * @returns {boolean}
 */


function isFormItemValid(start, end) {
    return (end > start);
}

const cut_content_formData = [
    {type: "settings", position: "label-left", labelWidth: 100, inputWidth: 180, offsetLeft: 35},
    {type: "input", label: "ID", className: "formlabel", name: "ID", hidden: true},
    {type: "input", label: "Start time", className: "formlabel", name: "start", required: true, validate:"([0-5][0-9][:])[0-5][0-9]",  note: {
            text: "Please use [00:04, 01:40] formats"
        }},
    {type: "input", label: "End time", className: "formlabel", name: "end", required: true,  validate:"([0-5][0-9][:])[0-5][0-9]",  note: {
            text: "Please use [00:04, 01:40] formats. \n Must be greater than start time"
        }},
    {type: "label", name: "valid", label: ""},
    {type: "button", label: "Continue", className: "formlabel", value: "Continue", name: "submit"}
];


