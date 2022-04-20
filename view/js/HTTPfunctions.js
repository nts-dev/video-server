function saveMediaInfo() {
    const mediaId = media_files_grid.getSelectedRowId();
    if (!mediaId) {
        dhtmlx.alert("Please select media Item from main content");
        return
    }
    const timelineInfoIframe = commentLayout.cells('c').getFrame();
    const content = timelineInfoIframe.contentWindow.tinyMCE.activeEditor.getContent();


    $.ajax({
        url: TIMELINE_URL + "4",
        type: "POST",
        data: {upload_id: mediaId, text: content},
        dataType: "json",
        success: function (response) {
            dhtmlx.message({title: 'Success', text: "Update success"});
        }
    });
}


function saveMediaComment() {
    const mediaId = media_files_grid.getSelectedRowId();

    if (!mediaId) {
        dhtmlx.alert("Please select media Item from main content");
        return
    }

    const commentIframe = commentLayout.cells('b').getFrame();
    const content = commentIframe.contentWindow.tinyMCE.activeEditor.getContent();


    $.ajax({
        url: COMMENT_URL + "4",
        type: "POST",
        data: {upload_id: mediaId, text: content},
        dataType: "json",
        success: function (response) {
            dhtmlx.message({title: 'Success', text: "Update success"});
        }
    });
}


function onCourses_gridRowSelect(id) {
    if (id < 1) return;
    PROJECT_ID = id;
    mediaTreeGridState.project = id;
    ModuleContentGrid.clearAndLoad(MODULE_URL + "7&id=" + id);

    mediaTreeGrid.clearAndLoad(video_cut_url + "6&project=" + id);
}


function onModules_toolbarClicked(id) {

    const course = courses_grid.getSelectedItemId();
    const contentId = ModuleContentGrid.getSelectedRowId();
    // const course = projectFromProject.id
    switch (id) {
        case 'new':
            addContentRecord(course, contentId);
            break;
        case 'delete':
            deleteContentRecord(course, contentId);
            break;
        case 'default':
            onCourses_gridRowSelect(course);
            break;

        case 'all':
            ModuleContentGrid.clearAndLoad(MODULE_URL + "1");
            break;
    }
}

function addContentRecord(courseId) {

    if (courseId == null) {
        dhtmlx.alert({
            type: "alert-error",
            text: "Please select a record from Projects",
            title: "Error!"
        });
        return;
    }

    let post_data = {title: "new", description: "new", subject_id: courseId};

    $.ajax({
        method: 'POST',
        url: MODULE_URL + "4",
        data: post_data,
        statusCode: {
            401: function () {
                dhtmlx.alert("Not authenticated");
            }
        },
        success: function (response) {

            if (response != null) {

                dhtmlx.message({title: 'Success', text: 'New record added. \n \n Fill the form to save your changes'});
                ModuleContentGrid.clearAndLoad(MODULE_URL + "7&id=" + courseId, function () {
                    ModuleContentGrid.selectRowById(response.id);
                });
            } else {
                dhtmlx.alert({
                    type: "alert-error",
                    text: "An error occurred!",
                    title: "Error!"
                });
            }
        }
    });
}

function deleteContentRecord(courseId, contentId) {

    if (contentId == null) {
        dhtmlx.alert({
            type: "alert-error",
            text: "Please select a record to delete",
            title: "Error!"
        });
        return;
    }


    dhtmlx.confirm({
        title: "Confirm",
        type: "confirm",
        text: "Delete this content?",
        callback: function (ok) {
            if (ok) {
                $.ajax({
                    url: MODULE_URL + '3',
                    type: "POST",
                    data: {id: contentId, user_id: content_form.getItemValue("user_id")},
                    success: function (response) {
                        if (response.success) {
                            dhtmlx.message({title: 'Success', text: response.message});
                            ModuleContentGrid.clearAndLoad(MODULE_URL + "7&id=" + courseId);
                        } else {
                            dhtmlx.alert({type: "alert-error", text: "An error occurred!"});
                        }
                    }
                });
            } else {
                return false;
            }
        }
    });
}


function sortContent(rowId, course, type, nextId, index) {
    $.ajax({
        url: url + "35", type: "POST", data: {id: rowId, action: type, nextId: nextId},
        success: function (response) {
            var parsedJSON = eval('(' + response + ')');
            contentIndex = index;
            if (parsedJSON != null && parsedJSON.status == 'success') {
                dhtmlx.message({title: 'Success', text: parsedJSON.message});
                ModuleContentGrid.clearAndLoad(MODULE_URL + "7&id=" + course)
            }
        }
    });
}


function onModulecontentGridRowSelect(id) {
    if (id < 1) return;
    content_form.clear();
    content_form.load(MODULE_URL + '2&id=' + id);
    media_files_grid.clearAndLoad(VIDEO_URL + '7&id=' + id);
}


function onModules_toolbar_formClicked(id) {

    if (id == "save") {

        var contentId = ModuleContentGrid.getSelectedRowId();

        if (contentId == null) {
            dhtmlx.alert({
                type: "alert-error",
                text: "No Record Selected!",
                title: "Error!"
            });
            return;
        }

        const callback = function (response) {

            if (response != null) {

                dhtmlx.message({title: 'Success', text: "Successfully Updated"});

                var courseId = courses_grid.getSelectedItemId();
                ModulecontentGrid.updateFromXML(MODULE_URL + "7&id=" + courseId, true, true);
            }
        };

        content_form.send(MODULE_URL + "5", "post", callback);
    }
}


const MediaContainer = {
    isMedia: false,
    type: null
}

/**
 *
 *
 *
 * Return object MediaContainer with bool and either video or audio from a string scan
 */
function getContainerType(url) {
    if (url === null) return MediaContainer;

    for (let vid of MEDIA_TYPE_VIDEO_SET)
        if (url.includes(vid)) {
            MediaContainer.isMedia = true;
            MediaContainer.type = "video";
        }

    if (MediaContainer.isMedia) return MediaContainer;

    for (let aud of MEDIA_TYPE_AUDIO_SET)
        if (url.includes(aud)) {
            MediaContainer.isMedia = true;
            MediaContainer.type = "video";
        }

    return MediaContainer;
}

function onMedia_files_toolbarClicked(id) {

    const rowId = ModuleContentGrid.getSelectedRowId();
    const mediaId = media_files_grid.getSelectedRowId();

    switch (id) {

        case 'new':

            if (rowId == null) {
                dhtmlx.alert({
                    type: "alert-error",
                    text: "No Content Selected!",
                    title: "Error!"
                });
                return;
            }

            uploadFile(rowId, 'new');
            break;

        case 'replace':

            if (rowId == null) {
                dhtmlx.alert({
                    type: "alert-error",
                    text: "No Content Selected!",
                    title: "Error!"
                });
                return;
            }

            uploadFile(rowId, 'replace');

            break;

        case 'play':

            if (mediaId == null) {
                dhtmlx.alert({
                    type: "alert-error",
                    text: "No Media Selected!",
                    title: "Error!"
                });
                return;
            }

            const hashColIndex = media_files_grid.getColIndexById("hash");
            const uriColIndex = media_files_grid.getColIndexById("url");
            const hash = media_files_grid.cells(mediaId, hashColIndex).getValue();
            const uri = media_files_grid.cells(mediaId, uriColIndex).getValue();
            const container = getContainerType(uri);
            /**
             * TODO implement hash
             * @type {{id, title: string, hash}}
             */
            const objMedia = {
                id: mediaId,
                url: uri,
                hash: hash,
                title: "media title",
                video: container.type,
            };

            startMediaPlayerWindow(objMedia);
            break;

        case 'delete':

            if (mediaId == null) {
                dhtmlx.alert({
                    type: "alert-error",
                    text: "No Media Selected!",
                    title: "Error!"
                });
                return;
            }

            dhtmlx.confirm({
                title: "Confirm",
                type: "confirm",
                text: "Delete this file?",
                callback: function (ok) {
                    if (ok) {

                        $.ajax({
                            url: VIDEO_URL + '3',
                            type: "POST",
                            data: {id: mediaId, user_id: TRAINEE.id},
                            success: function (response) {
                                if (response.success) {
                                    dhtmlx.message({title: 'Success', text: response.message});
                                    media_files_grid.clearAndLoad(VIDEO_URL + '7&id=' + rowId);
                                } else {
                                    dhtmlx.alert({type: "alert-error", text: "An error occurred!"});
                                }
                            }
                        });
                    } else {
                        return false;
                    }
                }
            });
            break;

        case 'up':

            if (mediaId == null) {
                dhtmlx.alert({
                    type: "alert-error",
                    text: "No Media Selected!",
                    title: "Error!"
                });
                return;
            }

            var rowIndex = media_files_grid.getRowIndex(mediaId);
            var index = rowIndex - 1;
            var nextId = media_files_grid.getRowId(index);

            if (nextId)
                sortMedia(mediaId, rowId, 'up', nextId, index);
            else
                dhtmlx.alert('Cant sort further');
            break;

        case 'down':

            if (mediaId == null) {
                dhtmlx.alert({
                    type: "alert-error",
                    text: "No Media Selected!",
                    title: "Error!"
                });
                return;
            }

            var rowIndex = media_files_grid.getRowIndex(mediaId);
            var index = rowIndex + 1;
            var nextId = media_files_grid.getRowId(index);

            if (nextId)
                sortMedia(mediaId, rowId, 'down', nextId, index);
            else
                dhtmlx.alert('Cant sort further');
            break;

        case 'download':

            if (mediaId == null) {
                dhtmlx.alert({
                    type: "alert-error",
                    text: "No Media Selected!",
                    title: "Error!"
                });
                return;
            }

            window.location.href = url + "39&PROJECT_ID=" + PROJECT_ID + "&category=" + rowId + "&file=" + mediaId;
            break;

        case 'regenerate':

            if (mediaId == null) {
                dhtmlx.alert({
                    type: "alert-error",
                    text: "No Media Selected!",
                    title: "Error!"
                });
                return;
            }

            overWriteThumbTexts(alias);
            break;
    }
}


function downloadURI(uri, name) {
    const link = document.createElement("a");
    link.download = name;
    link.href = uri;
    link.click();
}


function overWriteThumbTexts(alias) {

    generatedAudioClipMainLayout.cells('b').progressOn();
    $.ajax({
        url: url + "40", type: "GET", data: {file: alias}, success: function (response) {
            var parsedJSON = eval('(' + response + ')');
            if (parsedJSON != null) {
                dhtmlx.message({title: 'Success', text: parsedJSON.message});

                overwriteThumbnails(alias);

            }
        }
    });
}

function overwriteThumbnails(alias) {
    mediaLayout.cells('a').progressOn();
    $.ajax({
        url: "/app/Stream/extractTime.php", type: "GET", data: {file: alias}, success: function (response) {
            var parsedJSON = eval('(' + response + ')');
            if (parsedJSON != null && parsedJSON.call !== 1) {

                if (parsedJSON.status == "fail")
                    generatedAudioClipMainLayout.cells('b').progressOff();
                else
                    generateSpriteWithTextsService(alias);
                dhtmlx.message({title: 'Success', text: parsedJSON.message});
            } else {
                mediaLayout.cells('a').progressOff();
                generatedAudioClipMainLayout.cells('b').progressOff();
                dhtmlx.alert(parsedJSON.message);
            }
        }
    });
}

function generateSpriteWithTextsService(alias) {
    $.ajax({
        url: "/app/Stream/TranscoderREST.php", type: "GET", data: {file: alias}, success: function (response) {
            var parsedJSON = eval('(' + response + ')');
            if (parsedJSON != null) {

                mediaLayout.cells('a').progressOff();
                generatedAudioClipMainLayout.cells('b').progressOff();
                dhtmlx.alert(parsedJSON.message);
            }
        }
    });
}

var fileUploadMainWindow;
var fileUploadWindow;
var fileUploadLayout;


function uploadFile(moduleId, action) {

    if (moduleId < 1 || PROJECT_ID < 1)
        return;

    fileUploadMainWindow = new dhtmlXWindows();
    fileUploadWindow = fileUploadMainWindow.createWindow("uploadpic_win", 0, 0, 500, 580);
    fileUploadWindow.center();
    fileUploadWindow.setText("Upload  file");

    // fileUploadWindow.attachLayout('F4');

    fileUploadLayout = fileUploadWindow.attachLayout('2E');
    fileUploadLayout.cells('a').hideHeader();
    fileUploadLayout.cells('b').hideHeader();
    fileUploadLayout.attachEvent("onContentLoaded", function (id) {

    });

    fileUploadMainWindow.attachEvent("onClose", function (win) {
        media_files_grid.clearAndLoad(VIDEO_URL + '7&id=' + moduleId, function () {

            $.ajax({
                url: url + "41",
                type: "GET",
                data: {file: media_files_grid.getSelectedRowId()},
                success: function (response) {
                    const parsedJSON = eval('(' + response + ')');
                }
            });
        });

        return true;
    });

    const formValues = {
        subject_id: null,
        module_id: null,
        title: null,
        description: null,
    }


    const formData = [

        {
            type: "block", list: [
                {type: "settings", offsetTop: 20, offsetLeft: 30, labelWidth: 100, inputWidth: 280,},
                {type: "combo", label: "Project /Subject", className: "formlabel", name: "subject_id", required: true},
                {type: "combo", label: "Category", className: "formlabel", name: "module_id", required: true},
                {type: "input", label: "Title", className: "formlabel", name: "title", required: true},
                {type: "input", label: "Description", className: "formlabel", name: "description", required: true},

                {type: "button", label: "Submit", className: "formlabel", name: "submit", value: "Attach file"},
            ]
        }
    ];


    const uploadfileForm = fileUploadLayout.cells('a').attachForm(formData);

    const projectCombo = uploadfileForm.getCombo("subject_id");
    const moduleCombo = uploadfileForm.getCombo("module_id");


    /**
     * TODO Project combo
     */

    projectCombo.load(PROJECT_URL + "6", function (response) {
        uploadfileForm.setItemValue("subject_id", PROJECT_ID);
    });

    projectCombo.attachEvent("onChange", function (id) {
        fileUploadLayout.cells('a').progressOn()
        uploadfileForm.setItemValue("module_id", null);
        moduleCombo.load(MODULE_URL + "8&id=" + id, function (response) {
            fileUploadLayout.cells('a').progressOff()
        });
    })


    uploadfileForm.attachEvent("onChange", function (name, value, state) {
        // your code here
        if (name === 'title')
            formValues.title = value;
    });

    uploadfileForm.attachEvent("onButtonClick", function (name) {
        // your code here
        // var values = uploadfileForm.getFormData();

        if (
            fileUploadWindow === null ||
            fileUploadLayout === null ||
            uploadfileForm.getItemValue("subject_id").trim() === '' ||
            uploadfileForm.getItemValue("module_id").trim() === '' ||
            uploadfileForm.getItemValue("title").trim() === '' ||
            uploadfileForm.getItemValue("description").trim() === '') {
            uploadfileForm.validate()
            return;

        }

        if (!uploadfileForm.validate()) return;

        attachUploadWindow(
            uploadfileForm.getItemValue("subject_id"),
            uploadfileForm.getItemValue("module_id"),
            uploadfileForm.getItemValue("title"),
            uploadfileForm.getItemValue("description"));

        // console.log(values)
    });


}


function attachUploadWindow(subject_id, module_id, title, description) {

    fileUploadLayout.cells('b').attachURL("/upload.php?subject_id=" + subject_id + "&module_id=" + module_id + "&title=" + title + "&description=" + description);
}


function attachFile(
    subject_id,
    module_id,
    title,
    description
) {
    const uploadFormBox = [
        {
            type: "block", list: [
                {type: "setting", offsetTop: 20, offsetLeft: 30},
                {
                    type: "fieldset", label: "Your file",
                    list: [{
                        type: "upload",
                        name: "myFiles",
                        inputWidth: 310,
                        url: VIDEO_URL + "4&subject_id=" + subject_id + "&module_id=" + module_id + "&" +
                            "title=" + title + "&description=" + description,
                        swfPath: "http://" + location.host + "/dhtmlxSuite5/codebase/ext/uploader.swf",
                        required: true
                    }]
                },
            ],
        }
    ];

    const fileForm = fileUploadLayout.cells('b').attachForm(uploadFormBox);

    fileForm.attachEvent("onFileAdd", function (realName) {
        let ext = getFileExtension(realName);
        if (!MEDIA_TYPE_AUDIO_SET.has(ext) && !MEDIA_TYPE_VIDEO_SET.has(ext) && !MEDIA_TYPE_MOODLE_SET.has(ext)) {
            dhtmlx.alert({title: "Error", text: realName + " should be a media type"})
        }
    });

    function getFileExtension(filename) {
        return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
    }

    fileForm.attachEvent("onUploadFile", function (realName, serverName) {

        dhtmlx.message('Upload success. Your file will be available shortly');
        clearForm(fileForm);
        fileUploadWindow.close();

        media_files_grid.clearAndLoad(VIDEO_URL + '7&id=' + module_id, function () {
//                vid_libGrid.clearAndLoad(url + '7&id=' + PROJECT_ID);
            $.ajax({
                url: url + "41",
                type: "GET",
                data: {file: media_files_grid.getSelectedRowId()},
                success: function (response) {
                    const parsedJSON = eval('(' + response + ')');
                }
            });
        });

        let ext = getFileExtension(realName);

        if (MEDIA_TYPE_MOODLE_SET.has(ext))
            return;

        $.ajax({
            url: VIDEO_URL + "9",
            type: "GET",
            data: {id: serverName},
            success: function (response) {

            }
        });

    });


    fileForm.attachEvent("onUploadFail", function (name) {
        dhtmlx.alert({
            title: "Error",
            text: "There was an error while uploading " + name + ". Please try again"
        })

    });

}


function clearForm(form) {
    form.getUploader('myFiles').clear();
}

function saveMediaCommentsContent() {
    var mediaId = media_files_grid.getSelectedRowId(),
        contentIframe = mediafileCommentLayout.cells('a').getFrame(),
        text = contentIframe.contentWindow.tinyMCE.activeEditor.getContent({format: 'html'});
    if (mediaId) {
        $.ajax({
            url: url + "17", type: "POST", data: {id: mediaId, text: text}, success: function (response) {
                var parsedJSON = eval('(' + response + ')');
                if (parsedJSON != null) {
                    dhtmlx.message({title: 'Success', text: parsedJSON.message});
                    //                media_files_grid.clearAndLoad(url + '14&id=' + rowId);
                }
            }
        });
    } else {
        dhtmlx.alert('Please select media file');
    }
}


function onMedia_files_gridRowSelect(id) {


    if (id === null || id < 1)
        return


    audioLanguageGrid.clearAndLoad(url + "48&media=" + id);
    commentLayoutForm.setItemValue("ID", id);

    const index = media_files_grid.getColIndexById("file_name");
    const mediaItem = media_files_grid.cells(id, index).getValue();
    commentLayoutForm.setItemValue("mediaName", mediaItem);
    updateMediaCommentIframeContent(id)
    updateMediaInfoIframeContent(id)
    updateScriptIframeContent(id)
}


function updateScriptIframeContent(id) {
    const _contentIframe = scriptMainLayout.cells('a').getFrame();
    _contentIframe.contentWindow.tinyMCE.activeEditor.setContent('');
    // $.ajax({
    //         url: url + "60", type: "POST", data: {id: id}, success: function (response) {
    //             const parsedJSON = eval('(' + response + ')');
    //             if (parsedJSON != null && parsedJSON.response === true)
    //                 _contentIframe.contentWindow.tinyMCE.activeEditor.setContent(parsedJSON.content);
    //
    //         }
    //     }
    // );
}


function updateMediaCommentIframeContent(id) {
    const _contentIframe = commentLayout.cells('b').getFrame();
    _contentIframe.contentWindow.tinyMCE.activeEditor.setContent('');
    $.ajax({
            url: COMMENT_URL + "2",
            type: "POST",
            data: {id: id},
            dataType: "json",
            success: function (response) {

                if (response.length > 0) {
                    _contentIframe.contentWindow.tinyMCE.activeEditor.setContent(response[0].content);
                }
            }
        }
    );
}


function updateMediaInfoIframeContent(id) {
    const _contentIframe = commentLayout.cells('c').getFrame();
    _contentIframe.contentWindow.tinyMCE.activeEditor.setContent('');

    $.ajax({
            url: TIMELINE_URL + "2",
            type: "POST",
            data: {id: id},
            dataType: "json",
            success: function (response) {

                if (response.length > 0) {
                    console.log(response[0].content)
                    _contentIframe.contentWindow.tinyMCE.activeEditor.setContent(response[0].content);
                }
            }
        }
    );
}


function onAudioLanguageTabToolbarClicked(id) {
    var language = audioLanguageTabToolbar.getListOptionSelected('language');
    var language_text = audioLanguageTabToolbar.getListOptionText('language', language);
    var media = media_files_grid.getSelectedRowId();
    var selectedLanguageID = audioLanguageGrid.getSelectedRowId();

    switch (id) {
        case "delete":
            if (selectedLanguageID) {
                audioSubtitleMainLayout.cells('a').progressOn();
                var audioLanguageItemID = audioLanguageGrid.cells(selectedLanguageID, 0).getValue();

                dhtmlx.confirm({
                    title: "Confirm",
                    type: "confirm",
                    text: "Delete this language?",
                    callback: function (ok) {
                        if (ok) {
                            $.ajax({
                                url: url + "58",
                                type: "GET",
                                data: {id: audioLanguageItemID},
                                success: function (response) {
                                    var parsedJSON = eval('(' + response + ')');
                                    if (parsedJSON != null) {
                                        dhtmlx.message({title: 'Success', text: parsedJSON.message});
                                        audioLanguageGrid.clearAndLoad(url + "48&media=" + media);
                                        audioSubtitleMainLayout.cells('a').progressOff();

                                    }
                                }
                            });
                        } else {
                            audioSubtitleMainLayout.cells('a').progressOff();
                            return false;
                        }
                    }
                });
            } else {
                dhtmlx.alert("please select media file")
                audioSubtitleMainLayout.cells('a').progressOff();
            }
            break;

        default:
            if (media) {
                audioSubtitleMainLayout.cells('a').progressOn();
                dhtmlx.confirm({
                    title: "Confirm",
                    type: "confirm",
                    text: "Add " + language_text,
                    callback: function (ok) {
                        if (ok) {
                            $.ajax({
                                url: url + "47",
                                type: "POST",
                                data: {media: media, language: language},
                                success: function (response) {
                                    var parsedJSON = eval('(' + response + ')');
                                    if (parsedJSON != null) {
                                        dhtmlx.message({title: 'Success', text: parsedJSON.message});
                                        audioLanguageGrid.clearAndLoad(url + "48&media=" + media);
                                        audioSubtitleMainLayout.cells('a').progressOff();

                                    }
                                }
                            });
                        } else {
                            audioSubtitleMainLayout.cells('a').progressOff();
                            return false;
                        }
                    }
                });
            } else {
                dhtmlx.alert("please select media file")
                audioSubtitleMainLayout.cells('a').progressOff();
            }
            break;

    }

}


function onOverlayFilesLayoutToolbarClicked(id) {
    var contentIframe = overlayFilesLayout.cells('a').getFrame();
    var content = contentIframe.contentWindow.getContent();
    var mediaid = media_files_grid.getSelectedRowId();
    var colIndex = media_files_grid.getColIndexById("Alias");
    var alias = media_files_grid.cells(mediaid, colIndex).getValue();
    switch (id) {
        case 'save':
            $.ajax({
                url: url + "42",
                type: "POST",
                data: {content: content, id: mediaid, lang: 1, field: 'overlaying_text'},
                success: function (response) {
                    var parsedJSON = eval('(' + response + ')');
                    dhtmlx.message({title: 'Success', text: parsedJSON.message});
                }
            });
            break;

        case 'generateThumbnailTexts':
            overWriteThumbTexts(alias);
            break;
    }
}

function onAudioLanguageGridSelected(id) {
    var audioLanguageItemID = audioLanguageGrid.cells(id, 0).getValue();
    audioGeneratorGrid.clearAndLoad(url + "49&id=" + audioLanguageItemID);
    audioMovieGrid.clearAndLoad(url + "55&id=" + audioLanguageItemID);
}

function onAudioGeneratorTabToolbarClicked(id) {
    var text_lang = audioLanguageGrid.getSelectedRowId();
    var mediaid = media_files_grid.getSelectedRowId();
    var audioLanguageItemID = audioLanguageGrid.cells(text_lang, 0).getValue();
    var count = audioGeneratorGrid.getRowsNum();
    var clipDetailID = audioGeneratorGrid.getSelectedRowId();
    var last_endTime = '';

    if (count > 0) {
        var last_id = audioGeneratorGrid.getRowId(audioGeneratorGrid.getRowsNum() - 1);
        last_endTime = audioGeneratorGrid.cells(last_id, 2).getValue();
    } else
        last_endTime = '00:01:00';

    switch (id) {
        case 'new':
            $.ajax({
                url: url + "50",
                type: "POST",
                data: {id: audioLanguageItemID, endTime: last_endTime},
                success: function (response) {
                    var parsedJSON = eval('(' + response + ')');
                    audioGeneratorGrid.clearAndLoad(url + "49&id=" + audioLanguageItemID);
                    dhtmlx.message({title: 'Success', text: parsedJSON.message});
                }
            });
            break;
        case 'delete':
            if (clipDetailID) {
                dhtmlx.confirm({
                    title: "Confirm",
                    type: "confirm",
                    text: "Delete this file?",
                    callback: function (ok) {
                        if (ok) {
                            $.ajax({
                                url: url + "54", type: "POST", data: {id: clipDetailID}, success: function (response) {
                                    var parsedJSON = eval('(' + response + ')');
                                    audioGeneratorGrid.clearAndLoad(url + "49&id=" + audioLanguageItemID);
                                    dhtmlx.message({title: 'Success', text: parsedJSON.message});
                                }
                            });
                        } else {
                            return false;
                        }
                    }
                });
            } else
                dhtmlx.alert('No Item selected');

            break;

        case 'generate':
//            var text = audioTextLayout.getContent();
            generatedAudioClipMainLayout.cells('b').progressOn();
            $.ajax({
                url: "Google/tts_run.php",
                type: "GET",
                data: {id: mediaid, lang: text_lang},
                success: function (response) {
                    dhtmlx.message({title: 'Success', text: 'Action complete'});
                    audioGeneratorGrid.updateFromXML(url + "49&id=" + audioLanguageItemID);
                    generatedAudioClipMainLayout.cells('b').progressOff();

                }
            });

            break;

        case 'subtitle':
            generatedAudioClipMainLayout.cells('b').progressOn();
            $.ajax({
                url: url + "56",
                type: "POST",
                data: {id: audioLanguageItemID, path: "f_" + mediaid, lang: text_lang},
                success: function (response) {
                    var parsedJSON = eval('(' + response + ')');
                    dhtmlx.message({title: 'Success', text: parsedJSON.message});
                    generatedAudioClipMainLayout.cells('b').progressOff();
                }
            })
            break;

        case 'overlaying':
            var colIndex = media_files_grid.getColIndexById("Alias");
            var alias = media_files_grid.cells(mediaid, colIndex).getValue();
            overWriteThumbTexts(alias);
            break;
    }

}

function onAudioTextTabToolbarClicked(id) {
    var mediaid = media_files_grid.getSelectedRowId();
    var audioItemId = audioGeneratorGrid.getSelectedRowId();
    var colIndex = audioGeneratorGrid.getColIndexById("SortID");
    var sort = audioGeneratorGrid.cells(audioItemId, colIndex).getValue();
    var text = audioTextLayout.getContent();
    var text_lang = audioLanguageGrid.getSelectedRowId();
    var audioLanguageItemID = audioLanguageGrid.cells(text_lang, 0).getValue();


    if (id === "save") {
        if (audioItemId)
            $.ajax({
                url: url + "51",
                type: "POST",
                data: {media: mediaid, item: audioItemId, sort: sort, content: text, lang: text_lang},
                success: function (response) {
                    var parsedJSON = eval('(' + response + ')');
                    audioGeneratorGrid.clearAndLoad(url + "49&id=" + audioLanguageItemID);
                    dhtmlx.message({title: 'Success', text: parsedJSON.message});
                }
            });
    }
}

function onAudioGeneratorGridSelected(id) {
    audioMovieLayout.cells('b').detachObject(true);
    audioTextLayout.setContent(""); //unset content
    if (id) {
        var mediaid = media_files_grid.getSelectedRowId();
        var colIndex = audioGeneratorGrid.getColIndexById("SortID");
        var sort = audioGeneratorGrid.cells(id, colIndex).getValue();
        var lang = audioLanguageGrid.getSelectedRowId();
        $.ajax({
            url: url + "52", type: "POST", data: {id: id}, success: function (response) {
                var parsedJSON = eval('(' + response + ')');
                audioTextLayout.setContent(parsedJSON.content);
                // audioMovieLayout.cells('b').attachURL("play/audioplayer.php?file=" + sort + ".mp3&path=f_" + mediaid + "&lang=" + lang + "&version=" + Math.random());
            }
        });
    }

}


function onAudioGeneratorGridEditCell(stage, rId, cInd, nValue, oValue) {
    var field = audioGeneratorGrid.getColumnId(cInd);
    var lang = audioLanguageGrid.getSelectedRowId();
    var audioLanguageItemID = audioLanguageGrid.cells(lang, 0).getValue();
    if (stage === 2) {

        $.ajax({
            url: url + "53", type: "POST", data: {id: rId, value: nValue, field: field}, success: function (response) {
                var parsedJSON = eval('(' + response + ')');
                dhtmlx.message({title: 'Success', text: parsedJSON.message});
                audioGeneratorGrid.updateFromXML(url + "49&id=" + audioLanguageItemID);
            }
        });
    }
}

function onAudioMovieLayoutToolbarClicked(id) {
    var text_langId = audioLanguageGrid.getSelectedRowId();
    var mediaid = media_files_grid.getSelectedRowId();
    var audioLanguageItemID = audioLanguageGrid.cells(text_langId, 0).getValue();
    if (id === 'generate') {
        $.ajax({
            url: "Google/audioMovie.php",
            type: "POST",
            data: {id: audioLanguageItemID, path: "f_" + mediaid, lang: text_langId},
            success: function (response) {
                var parsedJSON = eval('(' + response + ')');
                dhtmlx.message({title: 'Success', text: parsedJSON.message});
                audioMovieGrid.updateFromXML(url + "55&id=" + audioLanguageItemID);
            }
        });
    }
}

function onAudioMovieGridSelected(id) {
    var mediaid = media_files_grid.getSelectedRowId();
    var lang = audioLanguageGrid.getSelectedRowId();
    audioMovieLayout.cells('b').detachObject(true);
    // audioMovieLayout.cells('b').attachURL("play/audioplayer.php?file=audiomovie.mp3&path=f_" + mediaid + "&lang=" + lang + "&version=" + Math.random() + "&src=1");
}


function audioTranslatorWindow(language, audio_dbID) {
    var audioTranslator = new dhtmlXWindows();
    var audioTranslatorWin = audioTranslator.createWindow("w1", "", "", 380, 180);
    audioTranslatorWin.setText('');
    audioTranslatorWin.centerOnScreen();

    var formData = [
        {
            type: "settings",
            position: "label-left",
            labelWidth: 140,
            inputHeight: 23,
            inputWidth: 140,
            offsetLeft: 10,
            offsetTop: 20,
            className: "formlabel",
            align: "right"
        },
        {type: "combo", name: "language", label: "Translate from " + language + " to", className: "formlabel"},
        {type: "button", name: "save", value: "Proceed", className: "formlabel", offsetLeft: 150}

    ];
    var addLanguageForm = audioTranslatorWin.attachForm(formData);
    var languageCombo = addLanguageForm.getCombo("language");
    var languageValues = availableAudioGrid.getAllRowIds();

    languageCombo.addOption([
        [1, "English"],
        [4, "Dutch"],
        [7, "German"],
        [6, "French"],
        [8, "Swahili"],
        [9, "Spanish"],
        [11, "Malaysia"]
    ]);


    addLanguageForm.attachEvent("onChange", function (name, value) {
        //your code here
        for (i = 0; i < languageValues.length; i++) {
            console.log(languageValues[i])
            if (parseInt(languageValues[i]) === parseInt(value))
                dhtmlx.alert("A version of this language already exist")
        }
    });


    addLanguageForm.attachEvent("onButtonClick", function (name) {
        if (name === "save") {
            var value = addLanguageForm.getItemValue("language");
            if (value) {
                for (i = 0; i < languageValues.length; i++) {
                    if (parseInt(languageValues[i]) === parseInt(value))
                        dhtmlx.alert("A version of this language already exist")
                    else {
                        audioTranslatorWin.hide()
                        audioTranslatorProgressWindow(audio_dbID, value)
                        break;
                    }

                }
            } else
                dhtmlx.alert("Please select language")
        }

    });


}


function onCourses_toolbarClicked(id) {
    switch (id) {
        case 'new':


            const newCourse = new dhtmlXWindows();
            const newCourseWin = newCourse.createWindow("w1", "", "", 380, 180);
            newCourseWin.setText('');
            newCourseWin.centerOnScreen();

            const formData = [
                {
                    type: "settings",
                    position: "label-left",
                    labelWidth: 60,
                    inputHeight: 23,
                    inputWidth: 240,
                    offsetLeft: 10,
                    offsetTop: 20,
                    className: "formlabel",
                    align: "right"
                },
                {type: "input", name: "title", label: "Title", className: "formlabel"},
                {type: "button", name: "save", value: "Save", className: "formlabel", offsetLeft: 70}

            ];

            const addTrainingForm = newCourseWin.attachForm(formData);

            addTrainingForm.attachEvent("onButtonClick", function (name) {

                if (addTrainingForm.getItemValue('title')) {
                    if (name == 'save') {
                        addTrainingForm.send(url + "4", "POST", function (loader, response) {
                            var parsedJSON = eval('(' + response + ')');
                            dhtmlx.message({title: 'Success', text: parsedJSON.message});
                            courses_grid.deleteChildItems(0);
                            courses_grid.loadXML(PROJECT_URL + "1");
                        });
                    }
                } else {
                    dhtmlx.alert('Employee field empty');
                }
            });

            break;
        case 'refresh':
            courses_grid.deleteChildItems(0);
            courses_grid.loadXML(PROJECT_URL + "1");

            break;

        case 'delete':
            //            var rowId = courses_grid.getSelectedItemId();
            const rowId = (PROJECT_ID) ? PROJECT_ID : courses_grid.getSelectedItemId();
            if (rowId) {
                dhtmlx.confirm({
                    title: "Confirm",
                    type: "confirm",
                    text: "Delete this course?",
                    callback: function (ok) {
                        if (ok) {
                            $.ajax({
                                url: url + "5", type: "POST", data: {id: rowId},
                                success: function (response) {
                                    var parsedJSON = eval('(' + response + ')');
                                    if (parsedJSON != null) {
                                        dhtmlx.message({title: 'Success', text: parsedJSON.message});
                                        courses_grid.deleteChildItems(0);
                                        courses_grid.loadXML(url + '19');
                                    }
                                }
                            });

                        } else {
                            return false;
                        }
                    }
                });
            } else {
                dhtmlx.alert('Please select record')
            }
            break;
    }
}


function onCourses_gridCellEdit(stage, rId, cInd, nValue, oValue) {
    // your code here
    const column = courses_grid.getColumnId(cInd);
    if (stage === 2) {
        $.ajax({
            url: url + "6", type: "POST", data: {id: rId, field: column, value: nValue},
            success: function (response) {
                var parsedJSON = eval('(' + response + ')');
                if (parsedJSON != null) {
                    dhtmlx.message({title: 'Success', text: parsedJSON.message});
                    courses_grid.clearAndLoad(url + '19');
                }
            }
        });
    }
}
