<?php


class XML
{

    static private function header()
    {
        ob_clean();
        header("Content-type:text/xml");
        echo "<?xml version='1.0'?>";
    }




    static public function videoGrid($resultArray)
    {
        self::header();
        echo "<rows >";
        if (sizeof($resultArray) > 0) {
            foreach ($resultArray as $cell) {
                echo "<row id = '" . $cell->id . "' >";
                echo "<cell><![CDATA[" . $cell->id . "]]></cell>";
                echo "<cell><![CDATA[" . $cell->title . "]]></cell>";
                echo "<cell><![CDATA[" . $cell->description . "]]></cell>";
                echo "<cell><![CDATA[" . $cell->user_first_name . "]]></cell>";
                echo "<cell><![CDATA[" . $cell->total_views . "]]></cell>";
                echo "<cell><![CDATA[" . $cell->updated_at . "]]></cell>";
                echo "<cell><![CDATA[" . $cell->disk . "]]></cell>";
                echo "<cell><![CDATA[" . $cell->videoLink_raw . "]]></cell>";
                echo "<cell><![CDATA[" . $cell->hash . "]]></cell>";
                echo "</row>";
            }
        }
        echo "</rows >";
    }

    static public function moduleGrid($resultArray)
    {
        self::header();
        echo "<rows >";
        if (sizeof($resultArray) > 0) {
            foreach ($resultArray as $cell) {
                echo "<row id = '" . $cell->id . "' >";
                echo "<cell><![CDATA[" . $cell->id . "]]></cell>";
                echo "<cell><![CDATA[" . $cell->title . "]]></cell>";
                echo "<cell><![CDATA[" . $cell->description . "]]></cell>";
                echo "<cell><![CDATA[" . $cell->updated_at . "]]></cell>";
                echo "</row>";
            }
        }
        echo "</rows >";
    }


    static public function form($resultArray)
    {
        self::header();
        echo "<data>";
        foreach ($resultArray as $field => $value) {
            echo '<' . htmlspecialchars($field) . '>' . htmlspecialchars($value) . '</' . htmlspecialchars($field) . '>';
        }
        echo "</data>";
    }

    static public function combo($resultArray)
    {
        self::header();

        echo "<complete>";

        foreach ($resultArray as $cell) {
            echo '<option value="' . $cell->id . '">';
            echo "<![CDATA[" . $cell->title . "]]>";
            echo '</option>';
        }
        echo "</complete>";

    }



    static public function projectCombo($resultArray)
    {
        self::header();

        echo "<complete>";

        foreach ($resultArray as $cell) {
            echo '<option value="' . $cell['id'] . '">';
            echo "<![CDATA[" . $cell['title'] . "]]>";
            echo '</option>';
        }
        echo "</complete>";

    }


}