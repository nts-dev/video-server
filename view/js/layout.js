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
const url = baseURL + "app/Stream/data.php?action=";
const PARENT_URL = baseURL + "api/session/"
const VIDEO_URL = PARENT_URL + "video.php?action=";
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
    'mov',
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
]

const MEDIA_TYPE_AUDIO_SET = new Set(MEDIA_TYPE_AUDIO);
const MEDIA_TYPE_VIDEO_SET = new Set(MEDIA_TYPE_VIDEO);


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

const courses_grid = tutAdminPrimary.cells('a').attachTree();

// courses_grid.setImagePath('lib/dhtmlxsuite5/skins/skyblue/imgs/dhxtree_skyblue/');
courses_grid.enableHighlighting('1');
courses_grid.enableDragAndDrop('1', true);
courses_grid.setSkin('dhx_skyblue');
courses_grid.enableItemEditor(1);
courses_grid.enableTreeImages(false);
courses_grid.enableTreeLines(true);

courses_grid.loadXML(PROJECT_URL + "1");

courses_grid.attachEvent("onXLE", function (grid_obj, count) {
    onCourses_gridRowSelect('10398');
    courses_grid.openItem('10398');
    tutAdminPrimary.cells('a').progressOff();
});

courses_grid.attachEvent("onXLS", function (grid_obj) {
    tutAdminPrimary.cells('a').progressOn();
});

parent_Module_layout = tutAdminPrimary.cells('b').attachLayout('1C');


const parentModuleTabbar = parent_Module_layout.cells("a").attachTabbar();
parentModuleTabbar.addTab("contentMain", "Contents Main", myWidth * 0.09);
parentModuleTabbar.addTab("uploader", "Moodle Videos", myWidth * 0.09);
parentModuleTabbar.addTab("subtitles_audio", "Subtitles & Audio", myWidth * 0.09);
// parentModuleTabbar.addTab("script", "Scripts", myWidth * 0.09);
parentModuleTabbar.setArrowsMode("auto");
parentModuleTabbar.tabs('contentMain').setActive();


//
const Module_layout = parentModuleTabbar.tabs('contentMain').attachLayout('3U');


Module_layout.cells('a').setText('Contents');
Module_layout.cells('a').setHeight(myHeight * 0.4);
Module_layout.cells('b').setWidth(myWidth * 0.28);
Module_layout.cells('b').setText('');
Module_layout.cells('c').hideHeader();

mediaFilesGridHeight = myHeight - ((myHeight * 0.24) + (myHeight * 0.13));


const ModulecontentGrid = Module_layout.cells('a').attachGrid();
ModulecontentGrid.setIconsPath('./preview/codebase/imgs/');
ModulecontentGrid.setHeader(["ID", "Content Name", "Description", "Date updated"]);
ModulecontentGrid.setInitWidthsP('7,*,*,15');
ModulecontentGrid.init();

const mediaPrimaryTabLayout = Module_layout.cells('c').attachLayout('1C');


const mediaLayoutTabbar = mediaPrimaryTabLayout.cells("a").attachTabbar();
mediaLayoutTabbar.addTab("media", "Media", myWidth * 0.1);
mediaLayoutTabbar.addTab("comment", "Comment / Info", myWidth * 0.1);
mediaLayoutTabbar.setArrowsMode("auto");
mediaLayoutTabbar.tabs('media').setActive();


const mediaLayout = mediaLayoutTabbar.cells('media').attachLayout('1C');
mediaLayout.cells('a').hideHeader();

const media_files_grid = mediaLayout.cells('a').attachGrid();
media_files_grid.setIconsPath('./preview/codebase/imgs/');

media_files_grid.setHeader(["ID", "Title", "Description", "Author", "Views", "Uploaded", "map", "Url", "Hash#",]);
media_files_grid.setColumnIds("ID,file_name,description,author,views,Uploaded,disk,url,hash");
media_files_grid.setInitWidthsP('5,15,*,8,5,10,*,*,*');
media_files_grid.init();


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

const media_files_toolbar = mediaLayout.cells('a').attachToolbar();
media_files_toolbar.setIconset("awesome");
media_files_toolbar.loadStruct('<toolbar>'
    + '<item type="button" id="new" text="Upload" img="fa fa-upload " /><item type="separator" id="sep_1" />'
    + '<item type="button" id="download" text="Download" img="fa fa-download " /><item type="separator" id="sep_2" />'
    + '<item type="button" id="play" text="Play" img="fa fa-play " /><item type="separator" id="sep_7" />'
    + '<item type="button" id="replace" text="Replace File" img="fa fa-copy " /><item type="separator" id="sep_3" />'
    + '<item type="button" id="up" text="Up" img="fa fa-arrow-up" />'
    + '<item type="button" id="down" text="Down" img="fa fa-arrow-down" /><item type="separator" id="sep_5" />'
    + '<item type="button" id="delete" text="Delete" img="fa fa-trash " /><item type="separator" id="sep_6" />'
    //        + '<item type="button" id="regenerate" text="Generate Thumbs Texts" img="fa fa-cog " /><item type="separator" id="sep_7" />'
    + '</toolbar>', function () {
});


const modules_toolbar_form = Module_layout.cells('b').attachToolbar();
modules_toolbar_form.setIconset("awesome");
modules_toolbar_form.loadStruct('<toolbar>'
    // + '<item type="button" id="new" text="New" img="fa fa-plus " /><item type="separator" id="sep_1" />'
    + '<item type="button" id="save" text="Save" img="fa fa-file " /><item type="separator" id="sep_2" />'
    // + '<item type="button" id="delete" text="Delete" img="fa fa-trash " /><item type="separator" id="sep_4" />'
    + '</toolbar>', function () {
});

modules_toolbar = Module_layout.cells('a').attachToolbar();
modules_toolbar.setIconset("awesome");
modules_toolbar.loadStruct('<toolbar>'
    + '<item type="button" id="new" text="New Content" img="fa fa-plus " /><item type="separator" id="sep_1" />'
    + '<item type="button" id="up" text="" img="fa fa-arrow-up" />'
    + '<item type="button" id="down" text="" img="fa fa-arrow-down" /><item type="separator" id="sep_3" />'
    + '<item type="button" id="default" text="Default" img="fa fa-th-list fa-5x " /><item type="separator" id="sep_5" />'
    + '<item type="button" id="all" text="Show All" img="fa fa-list-ol fa-5x" /><item type="separator" id="sep_6" />'
    + '<item type="button" id="delete" text="Delete" img="fa fa-trash" /><item type="separator" id="sep_4" />'
    + '</toolbar>', function () {
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


const mediaCommentTinyMCE = commentLayout.cells('b').attachURL(baseURL + "app/tinyMceDisplay_comments.php?id=mediacomment&name=mediacomment")

const mediaInfoTinyMCE = commentLayout.cells('c').attachURL(baseURL + "app/tinyMceDisplay_info.php?id=mediainfo&name=mediainfo")








