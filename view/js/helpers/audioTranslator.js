/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function audioTranslatorProgressWindow(audio_dbID, language) {
    var audioTranslatorProgress = new dhtmlXWindows();
    var audioTranslatorProgressWin = audioTranslatorProgress.createWindow("progressWin", "", "", 680, 200);
    audioTranslatorProgressWin.setText('');
    audioTranslatorProgressWin.centerOnScreen();
    var layout = audioTranslatorProgressWin.attachLayout('1C')
    layout.cells("a").hideHeader()
 
    layout.cells('a').attachURL("app/progressIndicator.php")

    var progressIndicatorIframe = layout.cells('a').getFrame();

    var mediaid = media_files_grid.getSelectedRowId();


    layout.attachEvent("onContentLoaded", function (id) {
        progressIndicatorIframe.contentWindow.updateProgress("8%", "Setting up environment...");


        $.ajax({
            url: "Google/languageTranslator.php", type: "GET", data: {audio: audio_dbID, video: mediaid, lang: language}, success: function (response) {
                var parsedJSON = eval('(' + response + ')');
                dhtmlx.message({title: 'Success', text: parsedJSON.message});
                progressIndicatorIframe.contentWindow.updateProgress("15%", "Translation started...");
                generateShortAudioClips(mediaid, language, parsedJSON.item)


            }
        });


        /* 
         * Generate short audio clips
         */
        function generateShortAudioClips(mediaid, language, audioItemID) {
            $.ajax({
                url: "Google/tts_run.php", type: "GET", data: {id: mediaid, lang: language}, success: function (response) {
                    dhtmlx.message({title: 'Success', text: 'Action complete'});
                    audioGeneratorGrid.updateFromXML(url + "49&id=" + audioItemID);
                    progressIndicatorIframe.contentWindow.updateProgress("40%", "Generating audio...");
                    generateAudioMovie(audioItemID, mediaid, language)
                }
            });
        }

        /* 
         * Generate a single long audio movie from texts
         */
        function generateAudioMovie(audioItemID, mediaid, language) {
            progressIndicatorIframe.contentWindow.updateProgress("56%", "Generating audio...");
            $.ajax({
                url: "Google/audioMovie.php", type: "POST", data: {id: audioItemID, path: "f_" + mediaid, lang: language}, success: function (response) {
                    var parsedJSON = eval('(' + response + ')');
                    dhtmlx.message({title: 'Success', text: parsedJSON.message});
                    progressIndicatorIframe.contentWindow.updateProgress("64%", "Generating audio...");
                    audioMovieGrid.updateFromXML(url + "55&id=" + audioItemID);
                    generateSubtitle(audioItemID, mediaid, language)

                }
            });
        }

        /* 
         * generate/ update subtitles
         */

        function generateSubtitle(audioItemID, mediaid, language) {
            $.ajax({url: url + "56", type: "POST", data: {id: audioItemID, path: "f_" + mediaid, lang: language}, success: function (response) {
                    var parsedJSON = eval('(' + response + ')');
                    dhtmlx.message({title: 'Success', text: parsedJSON.message});
                    progressIndicatorIframe.contentWindow.updateProgress("64%", "Generating subtitles...");
                    mp3hlsEncordingService(mediaid, language);
                }})
        }
        /* 
         * Encode mp3 to hls and include into manifest
         */

        function mp3hlsEncordingService(mediaid, language) {
            progressIndicatorIframe.contentWindow.updateProgress("80%", "Audio encoding started...");
            $.ajax({
                url: "app/Encoder/processMP3.php", type: "GET", data: {id: mediaid}, success: function (response) {
                    var parsedJSON = eval('(' + response + ')');

                    if (parsedJSON != null) {
                        progressIndicatorIframe.contentWindow.updateProgress("96%", "Audio encoding finishing...");
                        setTimeout(function () {
                            progressIndicatorIframe.contentWindow.updateProgress("99%", "Process finished");
                            availableAudioGrid.clearAndLoad(url + "57&id=" + mediaid);
                            audioLanguageGrid.clearAndLoad(url + "48&media=" + mediaid);
                            dhtmlx.message(parsedJSON.message)
                            layout.cells('a').detachObject(true);
                            audioTranslatorProgressWin.hide()
                        }, 5000);
                    }

                }
            });
        }

    });

}

