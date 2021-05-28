/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var audioSubtitleMainPrimaryLayout = parentModuleTabbar.tabs("subtitles_audio").attachLayout('1C');
        audioSubtitleMainPrimaryLayout.cells("a").hideHeader();



var audioSubtitleTabbar = audioSubtitleMainPrimaryLayout.cells("a").attachTabbar();
audioSubtitleTabbar.addTab("audios", "Audio Clips", myWidth * 0.09);
audioSubtitleTabbar.addTab("scripts", "Scripts", myWidth * 0.09);
audioSubtitleTabbar.setArrowsMode("auto");
audioSubtitleTabbar.tabs('audios').setActive();


var audioSubtitleMainLayout = audioSubtitleTabbar.tabs("audios").attachLayout('3W');

audioSubtitleMainLayout.cells('a').setWidth(myWidth * 0.1721);
audioSubtitleMainLayout.cells('a').setText("Language")
audioSubtitleMainLayout.cells('b').hideHeader()
audioSubtitleMainLayout.cells('c').hideHeader()

var audioLanguageGrid = audioSubtitleMainLayout.cells('a').attachGrid();
audioLanguageGrid.setHeader(["ID", "Language"]);
audioLanguageGrid.setColumnIds('id,language');
audioLanguageGrid.setInitWidthsP("10,*");
audioLanguageGrid.init();

var audioLanguageTabToolbar = audioSubtitleMainLayout.cells('a').attachToolbar();
audioLanguageTabToolbar.setIconset("awesome");
audioLanguageTabToolbar.loadStruct(url + "44");

var generatedAudioClipMainLayout = audioSubtitleMainLayout.cells('b').attachLayout('2E');
generatedAudioClipMainLayout.cells('a').setText("Audio Clip");


const generatedAudioTextsTabbar = generatedAudioClipMainLayout.cells('b').attachTabbar();
generatedAudioTextsTabbar.addTab("plain_texts", "Plain Texts", myWidth * 0.09);
generatedAudioTextsTabbar.addTab("web_vtt", "WebVTT texts", myWidth * 0.09);
generatedAudioTextsTabbar.setArrowsMode("auto");
generatedAudioTextsTabbar.tabs('plain_texts').setActive();



const subtitleMiniFilesLayout = generatedAudioTextsTabbar.tabs('web_vtt').attachLayout('1C');
subtitleMiniFilesLayout.cells('a').hideHeader();
subtitleMiniFilesLayout.cells('a').attachURL(baseURL + "app/file_code.php?id=filesFrame&name=filesIframe");




const plainTextsLayout = generatedAudioTextsTabbar.tabs('plain_texts').attachLayout('1C');
plainTextsLayout.cells('a').hideHeader();

const audioTextTabToolbar = plainTextsLayout.cells('a').attachToolbar();
audioTextTabToolbar.setIconset("awesome");
audioTextTabToolbar.loadStruct('<toolbar>'
        + '<item type="button" id="save" text="Save" img="fa fa-file" /><item type="separator" id="sep_2" />'
        + '</toolbar>');

var audioGeneratorTabToolbar = generatedAudioClipMainLayout.cells('a').attachToolbar();
audioGeneratorTabToolbar.setIconset("awesome");
audioGeneratorTabToolbar.loadStruct('<toolbar>'
        + '<item type="button" id="new" text="New" img="fa fa-plus" /><item type="separator" id="sep_1" />'
        + '<item type="button" id="delete" text="Delete" img="fa fa-trash " /><item type="separator" id="sep_4" />'
        + '<item type="button" id="generate" text="Generate Audio" img="fa fa-volume-down " /><item type="separator" id="sep_3" />'
        + '<item type="button" id="subtitle" text="Update Subtitle text" img="fa fa-cog" /><item type="separator" id="sep_2" />'
        + '<item type="button" id="overlaying" text="Update Overlaying text" img="fa fa-cog" /><item type="separator" id="sep_5" />'
        + '</toolbar>');

var audioTextLayout = plainTextsLayout.cells('a').attachEditor();

var audioMovieLayout = audioSubtitleMainLayout.cells('c').attachLayout('2E');
audioMovieLayout.cells('a').setText('Audio Movie Grid');
audioMovieLayout.cells('b').setText('Audio Tester');
// audioMovieLayout.cells('b').attachURL("/play/audioplayer.php?file=output_test.mp3");


var audioMovieLayoutToolbar = audioMovieLayout.cells('a').attachToolbar();
audioMovieLayoutToolbar.setIconset("awesome");
audioMovieLayoutToolbar.loadStruct('<toolbar>'
        + '<item type="button" id="generate" text="Generate Audio Movie" img="fa fa-volume-down " /><item type="separator" id="sep_1" />'
        + '<item type="button" id="delete" text="Delete" img="fa fa-trash " /><item type="separator" id="sep_2" />'
        + '</toolbar>');

var audioGeneratorGrid = generatedAudioClipMainLayout.cells('a').attachGrid();
audioGeneratorGrid.setHeader(["Sort", "Begin", "End", "", "Updated", "Status"]);
audioGeneratorGrid.setColumnIds('SortID,BeginTime,EndTime,item,Updated,Status');
audioGeneratorGrid.setColTypes('ro,ed,ed,ro,ro,ro');
audioGeneratorGrid.setInitWidthsP("10,*,*,0,16,10");

audioGeneratorGrid.init();


var audioMovieGrid = audioMovieLayout.cells('a').attachGrid();
audioMovieGrid.setHeader(["Audio Item", "Last updated"]);
audioMovieGrid.setColumnIds('item,updated');
audioMovieGrid.setInitWidthsP("*,*");
audioMovieGrid.setColTypes('ro,ro');
audioMovieGrid.init();
