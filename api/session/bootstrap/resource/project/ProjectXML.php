<?php


class ProjectXML implements ProjectResource
{

    private $projectsArray;

    public function __construct($resultArray = array())
    {
        $this->projectsArray = $resultArray;
    }

    static private function header()
    {
        ob_clean();
        header("Content-type:text/xml");
        echo "<?xml version='1.0'?>";
    }


    private function printXML(stdClass $obj, $isRoot = false)
    {

        $itemName = self::xmlEntities($obj->name);
        $itemId = $obj->id;
        $projectId = ProjectUtil::generateProjectId($itemId);

        echo "<item id='" . $obj->id . "' text='" . $projectId . "| " . $itemName . "'>" . PHP_EOL;
        $y = 0;
        foreach ($obj->children as $child) {
            ++$y;
            $this->printXML($child, $y);
        }
        echo '</item>';
    }


    private static function xmlEntities($string): string
    {
        return strtr(
            $string, array(
                "<" => "&lt;",
                ">" => "&gt;",
                '"' => "&quot;",
                "'" => "&apos;",
                "&" => "&amp;",
            )
        );
    }


    function out()
    {
        self::header();
        echo '<tree id="0">';

        $gridData = array();
        $root = array();
        foreach ($this->projectsArray as $cell) {
            if (!isset($gridData[$cell['id']])) {
                $gridData[$cell['id']] = new stdClass;
                $gridData[$cell['id']]->children = array();

                $obj = $gridData[$cell['id']];
                $obj->id = $cell['id'];
                $obj->name = $cell['title'];
                $obj->parent_id = $cell['parent_id'];

                if ($cell['parent_id'] == 0) {
                    $root[] = $obj;
                } else {
                    if (!isset($gridData[$cell['parent_id']])) {
                        $gridData[$cell['parent_id']] = new stdClass;
                        $gridData[$cell['parent_id']]->children = array();
                    }
                    $gridData[$cell['parent_id']]->children[$cell['id']] = $obj;
                }
            }
        }


        $x = 0;
        foreach ($root as $obj) {
            ++$x;
            $this->printXML($obj, true);
        }
        echo '</tree>';
    }
}