let myWidth, myHeight, global_skin = 'dhx_terrace', grid_skin = 'dhx_web';

/**
 *
 *
 *
 *
 *
 *
 *
 *
 *
 * API END POINTS
 *
 * @type {string}
 */
var url = baseURL + "app/Stream/data.php?action=";
const PARENT_URL = "https://" + location.host + "/api/session/";
var VIDEO_URL = PARENT_URL + "video.php?action=";
const MODULE_URL = PARENT_URL + "module.php?action=";
const PROJECT_URL = PARENT_URL + "project.php?&action=";
const COMMENT_URL = PARENT_URL + "comment.php?action=";
const TIMELINE_URL = PARENT_URL + "timelineInfo.php?action=";


let media_file = '';
let mediaFilesGridHeight = 0;

const MEDIA_TYPE_VIDEO = [
    //videos
    'mp4',
    'MP4',
    'MKV',
    'mkv',
    'AVI',
    'avi',
    'MOV',
    'mov'
];

const MEDIA_TYPE_AUDIO = [
    //audio
    'WAV',
    'wav',
    'AIFF',
    'aiff',
    'AAC',
    'aac',
    'OGG',
    'ogg',
    'WMA',
    'wma',
    'MP3',
    'mp3'
];

var MEDIA_TYPE_MOODLE = ['h5p'];

const MEDIA_TYPE_AUDIO_SET = new Set(MEDIA_TYPE_AUDIO);
const MEDIA_TYPE_VIDEO_SET = new Set(MEDIA_TYPE_VIDEO);
var MEDIA_TYPE_MOODLE_SET = new Set(MEDIA_TYPE_MOODLE);


if (typeof (window.innerWidth) == 'number') {

//Non-IE

    myWidth = window.innerWidth;
    myHeight = window.innerHeight;

} else if (document.documentElement &&
    (document.documentElement.clientWidth || document.documentElement.clientHeight)) {

//IE 6+ in 'standards compliant mode'

    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;

} else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {

//IE 4 compatible

    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;

}


dhtmlx.skin = global_skin;

let contentIndex = 0;
let mediaIndex = 0;
let PROJECT_ID = 0;
let fileId = 0;


const tutMainPrimary_layout = new dhtmlXLayoutObject({
    parent: "videoAppHomepage",
    pattern: "1C"
});

tutMainPrimary_layout.cells('a').hideHeader();


const tutAdminPrimary = tutMainPrimary_layout.cells("a").attachLayout('2U');

tutAdminPrimary.cells('a').setText('Projects');
tutAdminPrimary.cells('a').setWidth(myWidth * 0.2);

const projectLayout = tutAdminPrimary.cells('b').attachLayout('1C');
projectLayout.cells("a").hideHeader();


const courses_toolbar = tutAdminPrimary.cells('a').attachToolbar();
courses_toolbar.setIconset("awesome");
courses_toolbar.loadStruct('<toolbar>'
    + '<item type="button" id="refresh" text="Refresh" img="fa fa-sync " /><item type="separator" id="sep_1" />'
    // + '<item type="button" id="new" text="New Course" img="fa fa-plus " /><item type="separator" id="sep_2" />'
    + '<item type="button" id="delete" text="Delete" img="fa fa-trash " /><item type="separator" id="sep_3" />'
    + '</toolbar>', function () {
});
courses_toolbar.attachEvent("onClick", onCourses_toolbarClicked);

const courses_grid = tutAdminPrimary.cells('a').attachTree();

// courses_grid.setImagePath('lib/dhtmlxsuite5/skins/skyblue/imgs/dhxtree_skyblue/');
courses_grid.enableHighlighting('1');
courses_grid.enableDragAndDrop('1', true);
courses_grid.setSkin('dhx_skyblue');
courses_grid.enableItemEditor(1);
courses_grid.enableTreeImages(false);
courses_grid.enableTreeLines(true);
courses_grid.attachEvent("onSelect", onCourses_gridRowSelect);
courses_grid.attachEvent("onEditCell", onCourses_gridCellEdit);
courses_grid.loadXML(PROJECT_URL + "1");

courses_grid.attachEvent("onXLE", function (grid_obj, count) {
    onCourses_gridRowSelect('10398');
    courses_grid.openItem('10398');
    tutAdminPrimary.cells('a').progressOff();
});

courses_grid.attachEvent("onXLS", function (grid_obj) {
    tutAdminPrimary.cells('a').progressOn();
});

const parent_Module_layout = tutAdminPrimary.cells('b').attachLayout('1C');


const parentModuleTabbar = parent_Module_layout.cells("a").attachTabbar();
parentModuleTabbar.addTab("contentMain", "Contents Main", myWidth * 0.09);
parentModuleTabbar.addTab("uploader", "Moodle Videos", myWidth * 0.09);
parentModuleTabbar.addTab("subtitles_audio", "Subtitles & Audio", myWidth * 0.09);
// parentModuleTabbar.addTab("script", "Scripts", myWidth * 0.09);
parentModuleTabbar.setArrowsMode("auto");
parentModuleTabbar.tabs('contentMain').setActive();

const Module_layout = parentModuleTabbar.tabs('contentMain').attachLayout('3U');


Module_layout.cells('a').setText('Contents');
Module_layout.cells('a').setHeight(myHeight * 0.4);
Module_layout.cells('b').setWidth(myWidth * 0.28);
Module_layout.cells('b').setText('');
Module_layout.cells('c').hideHeader();

mediaFilesGridHeight = myHeight - ((myHeight * 0.24) + (myHeight * 0.13));

const modulesToolbar = Module_layout.cells('a').attachToolbar();
modulesToolbar.setIconset("awesome");
modulesToolbar.addButton('new', 1, 'New Content', 'fa fa-plus', 'fa fa-plus');
modulesToolbar.addSeparator('sep1', 2);
modulesToolbar.addButton('up', 3, 'Up', 'fa fa-arrow-up', 'fa fa-arrow-up');
modulesToolbar.addSeparator('sep2', 4);
modulesToolbar.addButton('down', 5, 'Down', 'fa fa-arrow-down', 'fa fa-arrow-down');
modulesToolbar.addSeparator('sep3', 6);
modulesToolbar.addButton('default', 7, 'Default', 'fa fa-th-list fa-5x', 'fa fa-th-list fa-5x');
modulesToolbar.addSeparator('sep3', 8);
modulesToolbar.addButton('all', 9, 'Show All', 'fa fa-list-ol fa-5x', 'fa fa-list-ol fa-5x');
modulesToolbar.addSeparator('sep3', 10);
modulesToolbar.addButton('delete', 11, 'Delete', 'fa fa-trash', 'fa fa-trash');
modulesToolbar.attachEvent("onClick", onModules_toolbarClicked);


const ModuleContentGrid = Module_layout.cells('a').attachGrid();
ModuleContentGrid.setIconsPath('./preview/codebase/imgs/');
ModuleContentGrid.setHeader(["ID", "Content Name", "Description", "Date updated"]);
ModuleContentGrid.setInitWidthsP('7,*,*,15');
ModuleContentGrid.attachEvent("onRowSelect", onModulecontentGridRowSelect);
ModuleContentGrid.init();

ModuleContentGrid.attachEvent("onXLE", function (grid_obj) {
    // ModuleContentGrid.selectRow(contentIndex);
    // const id = ModuleContentGrid.getRowId(contentIndex);
    // onModulecontentGridRowSelect(id);
    Module_layout.cells('a').progressOff();
});

ModuleContentGrid.attachEvent("onXLS", function (grid_obj) {
    Module_layout.cells('a').progressOn()
});

const content_formData = [
    {type: "settings", position: "label-left", labelWidth: 100, inputWidth: 230, offsetLeft: 35},
    {type: "input", label: "ID", className: "formlabel", name: "id", hidden: true},
    {type: "input", label: "User", className: "formlabel", name: "user_id", hidden: true},
    {type: "input", label: "ProjectID", className: "formlabel", name: "subject_id", hidden: true},
    {type: "input", label: "Title", className: "formlabel", name: "title"},
    // {type: "newcolumn"},
    {type: "input", label: "Description", className: "formlabel", name: "description"},
    {type: "calendar", label: "Date Updated", className: "formlabel", name: "updated_at"},
];
//

const content_form = Module_layout.cells('b').attachForm(content_formData);

const modules_toolbar_form = Module_layout.cells('b').attachToolbar();
modules_toolbar_form.setIconset("awesome");
modules_toolbar_form.addButton('save', 1, 'Save', 'fa fa-file', 'fa fa-file');
modules_toolbar_form.attachEvent("onClick", onModules_toolbar_formClicked);

const mediaPrimaryTabLayout = Module_layout.cells('c').attachLayout('1C');


const mediaLayoutTabbar = mediaPrimaryTabLayout.cells("a").attachTabbar();
mediaLayoutTabbar.addTab("media", "Media", myWidth * 0.1);
mediaLayoutTabbar.addTab("comment", "Comment / Info", myWidth * 0.1);
mediaLayoutTabbar.setArrowsMode("auto");
mediaLayoutTabbar.tabs('media').setActive();


const mediaLayout = mediaLayoutTabbar.cells('media').attachLayout('1C');
mediaLayout.cells('a').hideHeader();

const media_files_toolbar = mediaLayout.cells('a').attachToolbar();
media_files_toolbar.setIconset("awesome");
media_files_toolbar.addButton('new', 1, 'Upload', 'fa fa-upload', 'fa fa-upload');
media_files_toolbar.addSeparator('sep1', 2);
media_files_toolbar.addButton('download', 3, 'Download', 'fa fa-download', 'fa fa-download');
media_files_toolbar.addSeparator('sep2', 4);
media_files_toolbar.addButton('play', 5, 'Play', 'fa fa-play', 'fa fa-play');
media_files_toolbar.addSeparator('sep3', 6);
media_files_toolbar.addButton('replace', 7, 'Replace File', 'fa fa-copy', 'fa fa-copy');
media_files_toolbar.addSeparator('sep4', 8);
media_files_toolbar.addButton('up', 9, 'Up', 'fa fa-arrow-up', 'fa fa-arrow-up');
media_files_toolbar.addSeparator('sep5', 10);
media_files_toolbar.addButton('down', 11, 'Down', 'fa fa-arrow-down', 'fa fa-arrow-down');
media_files_toolbar.addSeparator('sep6', 12);
media_files_toolbar.addButton('delete', 13, 'Delete', 'fa fa-trash', 'fa fa-trash');
media_files_toolbar.attachEvent("onClick", onMedia_files_toolbarClicked);


var media_files_grid = mediaLayout.cells('a').attachGrid();
media_files_grid.setIconsPath('./preview/codebase/imgs/');

media_files_grid.setHeader(["ID", "Title", "Description", "Author", "Views", "Uploaded", "map", "Url", "Hash#",]);
media_files_grid.setColumnIds("ID,file_name,description,author,views,Uploaded,disk,url,hash");
media_files_grid.setInitWidthsP('5,15,*,8,5,10,*,*,*');
media_files_grid.attachEvent("onRowSelect", onMedia_files_gridRowSelect);
media_files_grid.init();

media_files_grid.attachEvent("onXLS", function (grid_obj) {
    mediaLayout.cells('a').progressOn();
});

media_files_grid.attachEvent("onXLE", function (grid_obj) {
    mediaLayout.cells('a').progressOff();

    media_files_grid.selectRow(media_files_grid.getRowsNum() - 1);
    var id = media_files_grid.getSelectedRowId();
    fileId = id;
    onMedia_files_gridRowSelect(id);
});

const commentLayout = mediaLayoutTabbar.cells('comment').attachLayout('3T');
commentLayout.cells('a').hideHeader();
commentLayout.cells('a').setHeight(50);
commentLayout.cells('b').setText('Comment');
commentLayout.cells('c').setText('Timeline Info');


const mediaItemData = [
    {type: "settings", position: "label-left", labelWidth: 100, inputWidth: 180, offsetLeft: 35},
    {type: "input", label: "ID", className: "formlabel", name: "ID"},
    {type: "newcolumn"},
    {type: "input", label: "Name", className: "formlabel", name: "mediaName", inputWidth: 250,},
];
const commentLayoutForm = commentLayout.cells('a').attachForm(mediaItemData);
commentLayoutForm.setReadonly("ID", true);
commentLayoutForm.setReadonly("mediaName", true);

const mediaCommentTinyMCE = commentLayout.cells('b').attachURL(baseURL + "app/tinyMceDisplay_comments.php?id=mediacomment&name=mediacomment");

const mediaInfoTinyMCE = commentLayout.cells('c').attachURL(baseURL + "app/tinyMceDisplay_info.php?id=mediainfo&name=mediainfo");








