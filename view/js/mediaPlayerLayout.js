// const playerWinWidth = 800;
// const playerWinHeightExact = mediaFilesGridHeight-70;


const playerWinWidth = 710;
const playerWinHeight = 680;

// const playerWinHeight = playerWinHeightExact > 560 ? playerWinHeightExact : 560;

function startMediaPlayerWindow(media) {

    const shareProps = {
        allowInfo: false,
        allowThumbs: false,
    }

    const playerMainWindow = new dhtmlXWindows();
    const playerWindow = playerMainWindow.createWindow("player_win", playerWinWidth, playerWinHeight, playerWinWidth, playerWinHeight);
    playerWindow.modal = true;
    // playerWindow.button("close").disable();
    playerWindow.button("park").disable();
    playerWindow.center();
    playerWindow.setText("");
    playerWindow.denyResize();
    playerWindow.denyPark();


    const playerLayout = playerWindow.attachLayout('2E');
    playerLayout.cells('a').hideHeader();
    playerLayout.cells('b').hideHeader();
    playerLayout.cells('a').setHeight(playerWinHeight * 0.75)
    playerLayout.cells('b').setHeight(playerWinHeight * 0.25);
    playerLayout.cells('a').attachURL(baseURL + "play/?id="
        + media.hash
        + '&showinfo=true&showthumbs='
        + shareProps.allowThumbs
        + "&identifier=" + TRAINEE.identifier
        + "&trainee=" + TRAINEE.id);


    const formData = [

        {type: "settings", position: "label-left", labelWidth: 100, inputWidth: 100, offsetLeft: 35},
        {type: "radio", name: "info", value: 1, label: "Comments / info"},
        {type: "newcolumn", width: 160},
        {type: "radio", name: "thumbnails", value: 2, label: "Sprit thumbnails"},
        // {type: "newcolumn", width: 100},
        // {type: "radio", name: "border", value: 3, label: "Boarder"},
    ];


    const shareLayout = playerLayout.cells('b').attachLayout('2E');
    shareLayout.cells('a').hideHeader();
    shareLayout.cells('b').hideHeader();


    const checkPropertiesForm = shareLayout.cells('a').attachForm(formData)


    const mediaUrl = baseURL + 'play?id=' + media.hash

    let embedLink = '<iframe ' +
        'src="' + mediaUrl + '&showinfo=' + shareProps.allowInfo + '&showthumbs=' + shareProps.allowThumbs + '" ' +
        'width="560" ' +
        'height="325" ' +
        'allowfullscreen></iframe>'


    checkPropertiesForm.attachEvent("onChange", function (name, value, state) {
        // your code here
        // if(state === false)
        //     checkPropertiesForm.uncheckItem(name, "1");
        switch (name) {
            case "info":
                shareProps.allowInfo = state
                break;
            case "thumbnails":
                shareProps.allowThumbs = state
                break;
        }
        embedLink = '<iframe ' +
            'src="' + mediaUrl + '&showinfo=' + shareProps.allowInfo + '&showthumbs=' + shareProps.allowThumbs + '" ' +
            'width="560" ' +
            'height="325" ' +
            'allowfullscreen></iframe>'

        updateEmbedLink(embedLink)

    });


    shareLayout.cells("b").attachURL(baseURL + "app/share_vidoe_code.php?id=filesFrame&name=filesIframe");


    playerWindow.attachEvent("onMaximize", function (win) {
        // your code here
        playerLayout.cells('a').setHeight(myHeight * 0.7);
    });

    playerWindow.attachEvent("onMinimize", function (win) {
        // your code here
        playerLayout.cells('b').setHeight(playerWinHeight * 0.3);
    });

    // playerWindow.attachEvent("onClose", function (win) {
    //     console.log(MediaContainer);
    //     console.log("Deactivating active container");
    //     MediaContainer.type = null;
    //     MediaContainer.isMedia = false;
    //     console.log(MediaContainer);
    //     playerWindow.close();
    // });

    shareLayout.attachEvent("onContentLoaded", function (id) {
        updateEmbedLink(embedLink)
    });

    function updateEmbedLink(embedLink) {
        const command = media.video ? "<%play:video=" + media.hash + "%>" : "<%play:audio=" + media.hash + "%>";
        const ifr = shareLayout.cells('b').getFrame();
        const content = "Iframe   " + embedLink + " \n \nUrl       " + mediaUrl + " \n \nCommand  " + command;
        ifr.contentWindow.setContent(content);
    }
}


