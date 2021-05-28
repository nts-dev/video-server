
<html>
    <head>
        <title>Integration with dhtmlxToolbar</title>
        <link rel="stylesheet" type="text/css" href="../../../codebase/dhtmlx.css"/>
        <script src="../../../codebase/dhtmlx.js"></script>

        <script>
            //var dhxWins, w1, myToolbar, myGrid;

            function doOnLoad() {
                dhxWins = new dhtmlXWindows();
                //dhxWins.attachViewportTo("winVP");
                w1 = dhxWins.createWindow("w1", 20, 30, 400, 280);
                w1.setText("dhtmlxToolbar");
                w1.button("close").disable();
                //
                bk_Statement_Toolbar = w1.attachToolbar();
                bk_Statement_Toolbar.addButton("export", 1, "Export");
                bk_Statement_Toolbar.addSeparator("sep1", 2);
                //
//                myGrid = w1.attachGrid();
//                myGrid.setImagePath("../../../codebase/imgs/");
//                myGrid.load("../common/grid.xml");
            }

            function doOnUnload() {
                if (dhxWins != null && dhxWins.unload != null) {
                    dhxWins.unload();
                    dhxWins = w1 = myToolbar = myGrid = null;
                }
            }

        </script>
    <html>
        <body>
            <a onclick="doOnLoad()">ff</a>
        </body>
    </html>
